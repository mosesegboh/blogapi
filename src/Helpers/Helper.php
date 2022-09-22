<?php

namespace App\Helpers;

use App\Entity\User;

class Helper
{
    const APPROVE = 1;
    const DECLINE = 2;
    const AUTH_USER = 'richard@yahoo.com';
    const TEST_USER_REG_EMAIL = 'goodemail@tester.com';
    const TEST_USER_PASSWORD = 'dangerman';
    const TEST_REL_USER = '/api/users/5';
    const TEST_DATE = '2022-09-17T15:36:06.445Z';
    const TEST_TEXT = 'What is Lorem Ipsum?';
    const FROM_EMAIL = 'sales@app.com';
    const TO_EMAIL = 'mosesegboh@yahoo.com';

    /**
     * Generates a random string for symfony test
     *
     * @return String
     */
    function generateRandomString($length = 10): String
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * loading User instance for relationship data fixtures for symfony test
     *
     * @return User
     */
    public function persistUser(): User
    {
        $user = new User();
        $user->setEmail($this->randomEmail());
        $user->setPassword(Helper::TEST_USER_PASSWORD);
        $user->setRoles($this->randomValue('role'));
        return $user;
    }

    /**
     * Generates a random string for symfony test
     *
     * return Mixed
     */
    public function randomValue($returnValue)
    {
        if ($returnValue == 'status') {
            $names = ['Verification Requested', 'Approved', 'Declined'];
            return $names[array_rand($names)];
        }

        if ($returnValue == 'role') {
            $names = ['ROLE_ADMIN', 'ROLE_BLOGGER'];
            $randomArray = [];
            array_push($randomArray, $names[array_rand($names)]) ;
            return $randomArray;
        }
    }

    /**
     * Generates a random string/email for symfony test
     *
     * return String
     */
    public function randomEmail(): String
    {
        $names = ['mosesegboh@yahoo.com', 'mazi@yahoo.com',
                    'richard@yahoo.com', 'korede@yahoo.com',
                    'kola@yahoo.com', 'james@yahoo.com',
                    'okay@yahoo.com', 'ajala@yahoo.com'
                    ];

        return $names[array_rand($names)];
    }

    /**
     * Generates a random string/Image for symfony test
     *
     * return String
     */
    public function randomImage(): String
    {
        $names = ['632327844ec53_Screenshot from 2021-09-11 21-58-20.png',
                  '6322e3c38d042_Screenshot from 2021-09-11 20-49-40.png',
                  '632321fc9db9b_Screenshot from 2021-09-11 21-07-02.png',
                  '632322fa56414_Screenshot from 2021-09-11 20-49-40.png',
                  '632326dcebb81_Screenshot from 2021-09-13 21-46-02.png',
                ];

        return $names[array_rand($names)];
    }
}