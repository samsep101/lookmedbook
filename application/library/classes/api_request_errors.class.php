<?php
    class ApiRequestErrors
    {
        const INCORRECT_PARAMS = 1;
        const INCORRECT_TOKEN = 2;
        const INCORRECT_LOGIN_OR_PASSWORD = 3;
        const EMAIL_ALREADY_REGISTRED = 4;
        const ACCOUNT_SAVE_ERROR = 5;
        const ACCOUNT_NOT_EXIST = 6;
        const CLINIC_NOT_EXIST = 7;
        const DISEASE_NOT_EXIST = 8;
        const DOCTOR_NOT_EXIST = 9;
        const SCHEDULES_NOT_EXIST = 10;
        const METRO_STATION_NOT_EXIST = 11;
        const DOCTORS_NOT_SEARCH = 12;
        const SPECIALTIES_NOT_EXIST = 13;
        const PURPOSE_OF_VISIT_TO_SPECIALTY_NOT_EXIST = 14;
        const CITIES_NOT_EXIST = 15;
        const WRONG_SESSION_KEY = 16;
        const SCHEDULE_NOT_EXIST = 17;
        const WRONG_DOCTOR = 18;
        const WRONG_CLINIC = 19;
        const WRONG_SPECIALTY = 20;
        const INCORRECT_SN_TYPE = 21;
        const METRO_STATIONS_NOT_EXIST = 22;
        const VISIT_NOT_EXIST = 23;
        const VISIT_RATING_ALREADY_EXIST = 24;
        const INCORRECT_DOCTOR_ADVICE_TYPE = 25;
        const INCORRECT_CLINIC_ADVICE_TYPE = 26;
        const DOCTOR_REVIEW_ALREADY_EXIST = 27;
        const CLINIC_REVIEW_ALREADY_EXIST = 28;
        const VISIT_WITH_ACCOUNT_AND_DOCTOR_NOT_EXIST = 29;
        const VISIT_WITH_ACCOUNT_AND_CLINIC_NOT_EXIST = 30;
        const DISEASES_NOT_EXIST = 31;
        const VISITS_BY_ACCOUNT_NOT_EXIST = 32;
        const INCORRECT_PARAMS_VALUE = 33;
		const SLOTS_NOT_EXIST = 35;
        const CITY_NOT_EXIST = 37;
        const PHONE_NOT_EXIST = 38;
        const PHONE_CONFIRMED = 39;
        const DOCTOR_REVIEWS_NOT_EXIST = 40;
        const WRONG_CODE = 41;
        const CLINICS_NOT_SEARCH = 42;
        const NOT_THAT_ACCOUNT_PHONE = 43;

        const INVALID_CONFIRM_CODE = 50;


        const PARSE_ERROR = 32700;
        const INVALID_REQUEST = 32600;
        const METHOD_NOT_FOUND = 32601;
        const INVALID_PARAMS = 32602;
        const INTERNAL_ERROR = 32603;
        const INVALID_ORGANIZATION = 32404;
        const INVALID_SERVICE = 32405;
        const INVALID_SLOT = 32406;
        const INVALID_BOOK_RECORD = 32407;
        const INVALID_RESOURCE = 32408;
        const INVALID_BOOK_STATE_TRANSITION = 32501;
        const SAVE_ERROR = 99;

        const INVALID_WIDGET_IDENTIFIER = 9000;
    }