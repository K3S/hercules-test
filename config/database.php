<?php
return [
    'dsn' => 'odbc:DSN=*LOCAL;DBQ=, QTEMP QGPL HERC;NAM=1;CMT=1;',
    'driver' => 'Pdo',
    'platform' => 'IbmDb2',
    'platform_options' => [
        'quote_identifiers' => true,
    ],
];