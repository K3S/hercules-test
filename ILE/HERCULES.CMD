
             CMD        PROMPT('Hercules Test')
 PHP:        PARM       KWD(PHP) TYPE(*CHAR) LEN(200) +
                          PROMPT('Path to PHP Executable') +
                          DFT('/QOpenSys/pkgs/bin/php')
 IFSPATH:    PARM       KWD(IFSPATH) TYPE(*CHAR) LEN(200) +
                          PROMPT('Path to IFS Herc App') +
                          DFT('/usr/local/k3s/utilities')
