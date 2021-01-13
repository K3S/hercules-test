#!/QOpenSys/pkgs/bin/bash

# Delete build directory
rm -rf /usr/local/hercules-test

# Delete save files
system "DLTOBJ OBJ(QGPL/HERCSAVFI) OBJTYPE(*FILE)";
system "DLTOBJ OBJ(QGPL/HERCSAVFL) OBJTYPE(*FILE)";
system "DLTOBJ OBJ(QGPL/HERCSAVF) OBJTYPE(*FILE)";