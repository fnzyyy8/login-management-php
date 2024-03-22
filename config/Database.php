<?php
function getDatabaseConfig() : array
{
    return [
        "database" => [
            "test"=>[
                "url" =>"pgsql:host=localhost;port=5432;dbname=php_login_management_test",
                "username" =>"postgres",
                "password" =>"postgres"
            ],
            "prod"=>[
                "url" =>"pgsql:host=localhost;port=5432;dbname=php_login_management",
                "username" =>"postgres",
                "password" =>"postgres"
            ],
        ]
    ];

}