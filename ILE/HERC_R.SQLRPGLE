      /free
     H DftActGrp(*No)

       Dcl-S isValid Ind inz(*off);

       Dcl-C SQLStateOK Const('00000');
       Dcl-C RowNotFound Const('02000');

       Dcl-S wkfound      Ind        inz(*off);
       Dcl-S wk_found     Char(1)    inz('N');

       // Main Procedure
       Dcl-PI HERC_R;
         lifter char(25);
         lift_wgt packed(3:0);
         lift_time char(19);
       End-PI;

      // ----------------------------------------------------------------------
      // SQL Settings
      // ----------------------------------------------------------------------
       Exec SQL
         set option commit = *none,
                   datfmt = *iso,
                closqlcsr = *endactgrp;

       exec sql
         insert into LIFTING (LIFTER, LIFT_WGT, LIFT_TIME)
           values(:lifter, :lift_wgt, :lift_time);

       *inlr = *on;
       return;
