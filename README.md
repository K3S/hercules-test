# The Hercules Test
How much can you lift?

# Installation
### Download Save File
Download the save file from [this page](https://github.com/K3S/hercules-test/releases).

### Create the Empty Save File
On your IBM i, run this command to create an empty save file
```
CRTSAVF FILE(QGPL/HERCSAVF) TEXT('Hercules Test Save File')
```

### FTP Save File to server
```
$ ftp <server ip>
$ bin
$ put HERCSAVF.SAVF
```

### Restore objects from save files
Run these commands from the IBM i command line to install the application.

First, restore the two save files. One contains library objects (HERCSAVFL) and the other objects (HERCSAVFI). This command extracts those two save files from HERCSAVF.
```
RSTOBJ OBJ(*ALL) SAVLIB(QGPL) DEV(*SAVF) SAVF(QGPL/HERCSAVF) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(QGPL)
```

This command extracts the library objects into library `HERC`.
```
RSTLIB SAVLIB(HERC) DEV(*SAVF) SAVF(QGPL/HERCSAVFL) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(HERC)
```

This command ensures that there is a directory to restore the IFS objects into. For this command, your `/path/to/install` should be the directory that you want to install hercules within. Thus `/path/to/install` will be the parent directory (e.g., `/usr/local`). And you'll want to append `hercules-test` onto the end (e.g., `/usr/local/hercules-test`).
```
CRTDIR DIR('/path/to/install/hercules-test')
```

This command restores the IFS objects into the directory created above. For this command, your `/path/to/install` should again be the parent directory of the hercules IFS objects (e.g. `/usr/local`).
```
RST DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('/usr/local' *INCLUDE '/path/to/install'))
```

# Running the test
If you have shell access, you can run the php console command directly
```bash
$ cd /usr/local/hercules-test && HERC_USER=MYUSER HERC_PWD=MYPASS console.php app:benchmark
```

If you don't have shell access, you can run the IBM i command
```
HERC/HERCULES IFSPATH('/usr/local/hercules-test') OUTPUTF('/tmp/hercules.log') DBUSER(MYUSER) DBPWD(MYPASS)         
```
This will place the output in the `hercules.log` file where you'll be able to view the test results.

## N.B. Database and Toolkit configuration
If you need to make configuration changes to the database connection or the toolkit, you can edit the top section of /usr/local/hercules-test/console.php.

