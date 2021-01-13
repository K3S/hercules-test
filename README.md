# The Hercules Test
How much can you lift?

# Installation
### Download Save File
Download the save file from [this page](https://github.com/K3S/hercules-test/releases).

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
CRTDIR DIR('/usr/local')
CRTDIR DIR('/usr/local/hercules-test')
RST DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('/usr/local' *INCLUDE '/path/to/install'))
```
For the last command, your `/path/to/install` should be the absolute path to the directory that you want to contain hercules-test.

# Running the test
If you have shell access, you can run the php console command directly
```bash
$ cd /usr/local/hercules-test && HERC_USER=MYUSER HERC_PWD=MYPASS console.php app:benchmark
```

If you don't have shell access, you can run the IBM i command
```
HERC/HERCULES IFSPATH('/usr/local/hercules-test') OUTPUTF('/tmp/hercules.log') DBUSER(MYUSER) DBPWD(MYPASS)         
```

## N.B. Database and Toolkit configuration
If you need to make configuration changes to the database connection or the toolkit, you can edit the top section of /usr/local/hercules-test/console.php.

