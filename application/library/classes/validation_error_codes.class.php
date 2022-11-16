<?php
    class ValidationErrorCodes{
        const WRONG_FULL_NAME_LENGTH = 2;
        const BLANK_NAME_PASSWORD = 3;
        const INVALID_EMAIL = 4;
        const INVALID_LOGIN = 5;

        const EMAIL_NOT_CONFIRMED = 32;
        const NOT_AUTHED = 100;

        const INVALID_PHONE = 14;
		const NEED_CHECK_PHONE = 144;
        const INVALID_NICK = 12;
        const WRONG_NICK_LENGTH = 13;

        const WRONG_CITY = 6;
        const WRONG_DATE = 7;
        const WRONG_SEX = 8;
        const ALREADY_REGISTERED = 74;
        const WRONG_PASSWORD = 10;
        const WRONG_ACCOUNT = 11;

        const NOT_CONFIRMED_ACCOUNT = 23;

        const WRONG_SCHEDULE = 77;
        const WRONG_SCHEDULE_DOCTOR = 80;
        const SCHEDULE_IS_BUSY = 81;

        const PASSWORD_NOT_MATCH = 90;
        const WRONG_REVIEW_DATA = 15;

        const OLD_DATE = 16;

        const WRONG_CONFIRM_CODE = 18;

        const WRONG_DISEASE = 60;

        const ERROR = 50;

        const UNKNOWN_POPUP = 113;

        const WRONG_VISIT = 130;

        const NOT_RESERVED = 150;

        const COOKIE_NOT_SET = 166;

        const EMAIL_NOT_SEND = 170;

        const ACCOUNT_ACTIVITY_NOT_WRITE = 177;

        const WRONG_DATA = 200;

        const WRONG_FIRST_NAME_LENGTH = 21;
        const WRONG_LAST_NAME_LENGTH = 22;
        const WRONG_SECOND_NAME_LENGTH = 23;
        const WRONG_ABOUT_LENGTH = 25;

        const WRONG_COUNT = 25;

		const WRONG_VALUE_FORMAT = 900;

		const IS_REQUIRED_FIELD = 901;

        const INVALID_FILE_TYPE = 201;

	    const NOT_CONFIRMED_PHONE = 555;

        const NO_ACCOUNT_RELATIONS = 26;
        const IMAGE_NOT_FOUND = 27;

        const NOT_DOCTOR_SPECIALTY_TO_CLINIC = 111;

        const INVALID_POSTCODE = 17;
		const ACCESS_DENIED = 403;
        const BIG_FILE_SIZE = 600;

        const FAILED_TO_DETERMINE_CITY = 1000;

		const UNKNOWN_PHONE_NUMBER = 1135;

        const DUPLICATED_RECORD = 1001;
		const ADD_TO_BASKET_ERROR = 10000;

        const EXISTING_DOCTOR_FIO = 112;

		const SHIPPING_ADDRESS_REQUIRED = 12345;
        const NOT_SAVED = 115;
        const NO_RESULT = 116;
        const WRONG_CSRF_TOKEN = 999;

        const UNDEFINED_PHONE_NUMBER = 36;
        const CANT_RESEND_CONFIRMED_CODE = 37;
    }