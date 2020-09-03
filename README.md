# The Hercules Test
How much can you lift?

# Installation
### FTP Save File to server
```
$ ftp <server ip>
$ bin
$ put HERCSAVF.SAVF
```

### Restore objects from save files
Run these commands from the IBM i command line to install the application:
```
RSTOBJ OBJ(*ALL) SAVLIB(QGPL) DEV(*SAVF) SAVF(QGPL/HERCSAVF) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(QGPL)
RSTLIB SAVLIB(HERC) DEV(*SAVF) SAVF(QGPL/HERCSAVFL) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(HERC)
CRTDIR DIR('/usr/local/k3s/utilities/hercules-test')
RST DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('/usr/local/k3s/utilities'))
```

# Running the test
If you have shell access, you can run the php console command directly
```bash
$ cd /usr/local/k3s/utilities/hercules-test && HERC_USER=MYUSER HERC_PWD=MYPASS console.php app:benchmark
```

If you don't have shell access, you can run the IBM i command
```
HERC/HERCULES IFSPATH('/usr/local/k3s/utilities/hercules-test') OUTPUTF('/usr/local/k3s/hercules.log') DBUSER(MYUSER) DBPWD(MYPASS)         
```
**Note: The default value for the IFSPATH parameter is '/usr/local/k3s/utilities'. This is not the path to the application. However, as listed [here](https://www.ibm.com/support/knowledgecenter/ssw_ibm_i_71/cl/parm.htm#PARM.EXPR) the maximum string length for an IBM i command *CHAR parameter default value is 32. The correct value should be (as shown above) `/usr/local/k3s/utilities/hercules-test`.

## N.B. Database and Toolkit configuration
If you need to make configuration changes to the database connection or the toolkit, you can edit the top section of /usr/local/k3s/utilities/hercules-test/console.php.

