<?php
    class NameGenerator
    {
        protected $female_last_names = array(
            'Глухова',
            'Фадеева',
            'Ермакова',
            'Абрамчик',
            'Козлова',
            'Мелешко',
            'Бриль',
            'Козел',
            'Медведева',
            'Коган',
            'Самец',
            'Петрова',
            'Сидорова',
            'Зуева',
            'Чуденцова',
            'Красивая',
            'Шломина',
            'Мелещеня',
            "Соколова",
            'Минска',
            "Марина"
        );
        protected $female_first_names = array(
            "Юлия",
            "Оксана",
            "Жанна",
            "Нина",
            "Светлана",
            "Марина",
            "Ольга",
            "Татьяна",
            "Мария",
            "Екатерина",
            "Мила",
            "Мая",
            "Алеся",
            "Олеся",
            "Александра",
            "Евгения",
            "Ирина",
            "Надежда",
            "Дарья",

        );
        protected $female_second_names = array(
            "Аркадьевна",
            "Валентиновна",
            "Васильевна",
            "Александрова",
            "Ренатовна",
            "Михайловна",
            "Дмитриевна",
            "Валерьевна",
            "Евгеньевна",
            "Андреевна",
            "Глебовна",
            "Петровна",
            "Юрьевна",
            "Петровна",
            "Павловна",
            "Вадимовна",
            "Григорьевна",
            "Мироновна"
        );

        protected $male_first_names = array(
            "Денис",
            "Андрей",
            "Владимир",
            "Влад",
            "Вадим",
            "Роман",
            "Александр",
            "Жан",
            "Петр",
            "Михаил",
            "Евгений",
            "Мирон",
            "Григорий",
            "Артем",
            "Ренат",
            "Дмитрий",
            "Егор",
            "Анатолий",
            "Глеб",
            "Сергей",
            "Джон"
        );
        protected $male_second_names = array(
            "Аркадьевич",
            "Валентинович",
            "Васильевич",
            "Александрович",
            "Ренатович",
            "Михайлович",
            "Дмитриевич",
            "Валерьевич",
            "Евгеньевич",
            "Андреевич",
            "Глебович",
            "Петрович",
            "Юрьевич",
            "Павлович",
            "Вадимович",
            "Григорьевич",
            "Миронович",
            "Сергеевич"
        );
        protected $male_last_names = array(
            'Глухов',
            'Фадеев',
            'Ермаков',
            'Абрамчик',
            'Козлов',
            'Мелешко',
            'Бриль',
            'Козел',
            'Медведев',
            'Коган',
            'Самец',
            'Петров',
            'Сидоров',
            'Зуев',
            'Чуденцов',
            'Красивый',
            'Шломин',
            'Мелещен',
            "Соколов",
            'Минск',
            "Марин"
        );

        protected $first_name;
        protected $second_name;
        protected $last_name;

        public function getFirstName()
        {
            return $this->first_name;
        }

        public function getLastName()
        {
            return $this->last_name;
        }

        public function getSecondName()
        {
            return $this->second_name;
        }

        public function getFullName()
        {
            return $this->first_name.' '.$this->second_name.' '.$this->last_name;
        }


        public function generateMaleName()
        {
            $this->first_name = $this->male_first_names[rand(0, count($this->male_first_names) - 1)];
            $this->second_name =  $this->male_second_names[rand(0, count($this->male_second_names) - 1)];
            $this->last_name = $this->male_last_names[rand(0, count($this->male_last_names) - 1)];
        }

        public function generateFemaleName()
        {
            $this->first_name = $this->female_first_names[rand(0, count($this->female_first_names) - 1)];
            $this->second_name = $this->female_second_names[rand(0, count($this->female_second_names) - 1)];
            $this->last_name = $this->female_last_names[rand(0, count($this->female_last_names) - 1)];
        }
    }