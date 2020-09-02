
             CMD        PROMPT('Hercules Test')
 PHP:        PARM       KWD(PHP) TYPE(*CHAR) LEN(200) +
                          PROMPT('Path to PHP Executable') +
                          DFT('/QOpenSys/pkgs/bin/php')
 IFSPATH:    PARM       KWD(IFSPATH) TYPE(*CHAR) LEN(200) +
                          PROMPT('Path to IFS Herc App') +
                          DFT('/usr/local/k3s/utilities')
 OUTPUTFILE: PARM       KWD(OUTPUTF) TYPE(*CHAR) LEN(200) +
                        PROMPT('File to send output to') +
                        DFT('/usr/local/k3s/hercules.log')
 DBUSER:     PARM       KWD(DBUSER) TYPE(*CHAR) LEN(10) +
                        PROMPT('User name for db connection')
 DBPWD:      PARM       KWD(DBPWD) TYPE(*CHAR) LEN(10) +
                        PROMPT('Password for db connection')
