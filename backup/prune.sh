
#!/bin/bash
BACKUP_DIR="/backups"
cd "$BACKUP_DIR" || exit 1

# Файлы дампов имеют формат: <dbname>_YYYYMMDD_HHMMSS.sql.gz
# Группируем по дате в имени файла (первые 8 цифр после последнего '_')
shopt -s nullglob
files=(*.sql.gz)

# Сортируем по дате (вторая часть имени)
sorted_files=()
while IFS= read -r line; do
	    sorted_files+=("$line")
    done < <(for f in "${files[@]}"; do
        # извлекаем YYYYMMDD
	    ts=$(echo "$f" | grep -oP '\d{8}(?=_\d{6}\.sql\.gz)')
	        if [[ -n "$ts" ]]; then
			        echo "$ts $f"
				    fi
			    done | sort | awk '{print $2}')

			    # Функция для определения, нужно ли оставить файл
			    keep_file() {
				        local fname="$1"
					    local file_date
					        file_date=$(echo "$fname" | grep -oP '\d{8}(?=_\d{6}\.sql\.gz)')
						    local file_epoch
						        file_epoch=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%s)
							    local now_epoch=$(date +%s)
							        local days_diff=$(( (now_epoch - file_epoch) / 86400 ))

								    # 1. Оставляем все дампы за последние 7 дней (включая сегодня)
								        if [ "$days_diff" -lt 7 ]; then
										        return 0
											    fi

											        # 2. Оставляем по одному на каждую из последних 4 полных недель (вт-вс? проще: по номеру недели)
												    # Определим номер недели года (ISO week). Берём дамп с минимальной датой в каждой неделе.
												        # Для простоты будем использовать week number и year.
													    local week_num=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%V)
													        local year=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%G)
														    local current_week=$(date +%V)
														        local current_year=$(date +%G)
															    # Если файл находится в диапазоне последних 4-х недель (учитывая переход года):
															        # Сравниваем (year, week_num) с текущим.
																    local week_diff=$(( (current_year - year) * 52 + current_week - week_num ))
																        if [ "$week_diff" -ge 0 ] && [ "$week_diff" -le 3 ]; then
																		       # Проверим, есть ли уже сохранённый файл этой недели (будем оставлять самый старый/первый)
																		       # Будем вести ассоциативный массив за пределами цикла, но здесь проще – функция не знает о других.
																		       # Сделаем иначе: основной цикл пройдёт по файлам и будет отмечать, какие оставить.
																		       return 0
																		   fi

																		       # 3. Оставляем по одному на последние 2 полных месяца (1-е число месяца)
																		   local file_month=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%Y-%m)
																		       local current_month=$(date +%Y-%m)
																		   local month_diff=$(echo "$(( ($(date -d "${current_month}-01" +%s) - $(date -d "${file_month}-01" +%s)) / 86400 / 30 ))" )
																		       if [ "$month_diff" -ge 0 ] && [ "$month_diff" -le 1 ]; then
																		       # Оставляем только первый попавшийся файл месяца (самый ранний). Реализуем через отдельный массив.
																		       return 0
																		   fi

																		       return 1
																		}

																		# Реализация с приоритетами
																		declare -A keep_map  # ключ: имя файла
																		declare -A week_kept  # ключ: "год-неделя"
																		declare -A month_kept # ключ: "год-месяц"

																		for f in "${sorted_files[@]}"; do
																		   file_date=$(echo "$f" | grep -oP '\d{8}(?=_\d{6}\.sql\.gz)')
																		       file_epoch=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%s)
																		   now_epoch=$(date +%s)
																		       days_diff=$(( (now_epoch - file_epoch) / 86400 ))

																		   # Ежедневные за 7 дней
																		       if [ "$days_diff" -lt 7 ]; then
																		       keep_map["$f"]=1
																		       continue
																		   fi

																		       # Недельные (последние 4 недели)
																		   week_num=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%V)
																		       year=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%G)
																		   current_week=$(date +%V)
																		       current_year=$(date +%G)
																		   week_diff=$(( (current_year - year) * 52 + current_week - week_num ))
																		       if [ "$week_diff" -ge 0 ] && [ "$week_diff" -le 3 ]; then
																		       key="${year}-W${week_num}"
																		       if [ -z "${week_kept[$key]}" ]; then
																		           keep_map["$f"]=1
																		               week_kept[$key]="$f"
																		       fi
																		       continue
																		   fi

																		       # Месячные (последние 2 месяца)
																		   file_month=$(date -d "${file_date:0:4}-${file_date:4:2}-${file_date:6:2}" +%Y-%m)
																		       current_month=$(date +%Y-%m)
																		   month_start_epoch=$(date -d "${current_month}-01" +%s)
																		       file_month_start_epoch=$(date -d "${file_month}-01" +%s)
																		   month_diff=$(( (month_start_epoch - file_month_start_epoch) / 2678400 ))  # приблизительно 31 день
																		       if [ "$month_diff" -ge 0 ] && [ "$month_diff" -le 1 ]; then
																		       key="${file_month}"
																		       if [ -z "${month_kept[$key]}" ]; then
																		           keep_map["$f"]=1
																		               month_kept[$key]="$f"
																		       fi
																		       continue
																		   fi

																		       # Остальные – удаляем позже
																		done

																		# Удаление файлов, не попавших в keep_map
																		for f in "${files[@]}"; do
																		   if [ -z "${keep_map[$f]}" ]; then
																		           echo "Removing old backup: $f"
																		           rm -f "$f"
																		       fi
																		done
