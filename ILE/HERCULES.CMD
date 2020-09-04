
             CMD        PROMPT('Hercules Test')
 PHP:        PARM       KWD(PHP) TYPE(*CHAR) LEN(200) +
                          PROMPT('Path to PHP Executable')
 IFSPATH:    PARM       KWD(IFSPATH) TYPE(*CHAR) LEN(200) +
                          PROMPT('Path to IFS Herc App')
 OUTPUTFILE: PARM       KWD(OUTPUTF) TYPE(*CHAR) LEN(200) +
                        PROMPT('File to send output to')
 DBUSER:     PARM       KWD(DBUSER) TYPE(*CHAR) LEN(10) +
                        PROMPT('User name for db connection')
 DBPWD:      PARM       KWD(DBPWD) TYPE(*CHAR) LEN(10) +
                        PROMPT('Password for db connection')
