# Install on customer server
# Restore
system "RSTOBJ OBJ(*ALL) SAVLIB(QGPL) DEV(*SAVF) SAVF(QGPL/HERCSAVF) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(QGPL)";
system "RSTLIB SAVLIB(WEB_5MOD) DEV(*SAVF) SAVF(QGPL/WEBLIB5MOD) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(WEB_5MOD)";

# Restore IFS objects
system "RST DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('*' *INCLUDE '/usr/local/k3s/utilities/hercules-test'))";
