#!/QOpenSys/pkgs/bin/bash

BASEDIR=$(pwd);
DDL=$(cat "${BASEDIR}/DDL/LIFTING.SQL");
LIBRARY="HERC";
UTILITIESDIR="/usr/local/k3s/utilities";

# Copy IFS objects to utilities directory
cp -rf "${BASEDIR}" "${UTILITIESDIR}";

# Compile sources
system -vs "CRTSQLRPGI OBJ(${LIBRARY}/HERC_R) SRCSTMF('${BASEDIR}/ILE/HERC_R.SQLRPGLE') COMMIT(*NONE) OPTION(*EVENTF *XREF) CVTCCSID(37)";
system -vs "CRTBNDCL PGM(${LIBRARY}/HERC_C) SRCSTMF('${BASEDIR}/ILE/HERC_C.CLLE')";
system -vs "CRTBNDCL PGM(${LIBRARY}/HERCULES) SRCSTMF('${BASEDIR}/ILE/HERCULES.CLLE')";
system -vs "CRTCMD CMD(${LIBRARY}/HERCULES) PGM(${LIBRARY}/HERCULES) SRCSTMF('${BASEDIR}/ILE/HERCULES.CMD')";
system -vs "CRTCMD CMD(${LIBRARY}/HERC_C) PGM(${LIBRARY}/HERC_C) SRCSTMF('${BASEDIR}/ILE/HERC_C.CMD')";

# Build table
system -v "RUNSQL SQL('${DDL}') COMMIT(*NONE) NAMING(*SQL)";

# Delete parent save file
system "DLTOBJ OBJ(QGPL/HERCSAVF) OBJTYPE(*FILE)";

# Create save files
system -v "CRTSAVF FILE(QGPL/HERCSAVFL) TEXT('Hercules')";
system -v "CLRSAVF FILE(QGPL/HERCSAVFL)";
system -v "CRTSAVF FILE(QGPL/HERCSAVFI) TEXT('Hercules IFS Objects')";
system -v "CLRSAVF FILE(QGPL/HERCSAVFI)";
system -v "CRTSAVF FILE(QGPL/HERCSAVF) TEXT('Hercules Library Objects')";
system -v "CLRSAVF FILE(QGPL/HERCSAVF)";

# Add objects to child save files
system -v "SAVLIB LIB(HERC) DEV(*SAVF) SAVF(QGPL/HERCSAVFL) TGTRLS(V7R2M0)";
system -v "SAV DEV('/qsys.lib/qgpl.lib/hercsavfi.file') OBJ(('${UTILITIESDIR}/hercules-test' *INCLUDE)) TGTRLS(V7R2M0)";

# Add child save files to parent save file
system -v "SAVOBJ OBJ(HERCSAVFL HERCSAVFI) LIB(QGPL) DEV(*SAVF) SAVF(QGPL/HERCSAVF)";

rm -rf "${UTILITIESDIR}/hercules-test";

# Remove child save files
system "DLTOBJ OBJ(QGPL/HERCSAVFI) OBJTYPE(*FILE)";
system "DLTOBJ OBJ(QGPL/HERCSAVFL) OBJTYPE(*FILE)";
