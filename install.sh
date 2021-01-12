# Install on customer server
# Restore
system -v "RSTOBJ OBJ(*ALL) SAVLIB(QGPL) DEV(*SAVF) SAVF(QGPL/HERCSAVF) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(QGPL)";
system -v "RSTLIB SAVLIB(HERC) DEV(*SAVF) SAVF(QGPL/HERCSAVFL) MBROPT(*ALL) ALWOBJDIF(*ALL) RSTLIB(HERC)";

# Restore IFS objects
system -v "CRTDIR DIR('/usr/local/k3s/utilities";
system -v "CRTDIR DIR('/usr/local/k3s/utilities/hercules-test')";
system -v "RST DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('/usr/local/k3s'))";
