# The Hercules Test
How much do you lift, bro?

# Installation
### Download Save File
Download the zipped save file from <a href="https://github.com/K3S/hercules-test/releases" target="_blank">this page</a>.

### Create the Empty Save File
On your IBM i, run this command to create an empty save file
```
CRTSAVF FILE(QGPL/HERCSAVF) TEXT('Hercules Test Save File')
```

### FTP Save File to server
This will insert the data into the save file. 
```
$ ftp <server ip>
$ bin
$ put HERCSAVF.SAVF
```

### Restore objects from save files
Run these commands from the IBM i command line to install the application.

First step is to restore the two save files. One contains library objects (HERCSAVFL) and the other objects (HERCSAVFI). 

This will extract those two save files from HERCSAVF.
```
RSTOBJ OBJ(*ALL) SAVLIB(QGPL) DEV(*SAVF) SAVF(QGPL/HERCSAVF) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(QGPL)
```

This will extract the library objects into library `HERC`.
```
RSTLIB SAVLIB(HERC) DEV(*SAVF) SAVF(QGPL/HERCSAVFL) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(HERC)
```

Last, this will restore the IFS objects into the directory created above. For this command, your `/<path*to*application>` should again be the parent directory of the hercules IFS objects (e.g. `/usr/local`), but you do not need to add `/herculues-test` to the end. 
```
RST DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('/usr/local' *INCLUDE '/<path*to*application>'))
```


# Running the test

To run Hercules via the IBM i command line (note to change the MYUSER and MYPASS to the user you want to run the test):
```
HERC/HERCULES PHP('/qopensys/pkgs/bin/php') IFSPATH('/usr/local/hercules-test') OUTPUTF('/tmp/hercules.log') DBUSER(MYUSER) DBPWD(MYPASS)         
```

The test results will be stored in `/tmp/hercules.log`.

To run Hercules via shell access (this will assume your PHP binary is located in `/QOpenSys/pkgs/bin`, also note to change the MYUSER and MYPASS to the user you want to run the test):

```
cd /usr/local/hercules-test
/QOpenSys/pkgs/bin/php console.php app:benchmark MYUSER MYPASS
```

The test results will be stored in `/tmp/hercules.log`.

# How to interpret the results

We need an explanation here

## N.B. Database and Toolkit configuration
If you need to make configuration changes to the database connection or the toolkit, you can edit the top section of /usr/local/hercules-test/console.php.

