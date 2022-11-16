<?php

class AccountSearchParams extends ModelSearchCriteria
{
  //ФИО: Фамилия Имя Отчество
  public $first_name = '';
  public $middle_name = '';
  public $last_name = '';

  //Телефонный номер:
  public $phone_number = '';

  //Email:
  public $email = '';

  public $page = 1;
  public $by_page = 150;
}