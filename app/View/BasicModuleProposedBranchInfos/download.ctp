<?php
 $line= $values[0]['BasicModuleProposedBranchInfo']; 
// debug($line);exit;
 $this->CSV->addRow(array_keys($line));
 foreach ($values as $value) {
      $line = $value['BasicModuleProposedBranchInfo'];
       $this->CSV->addRow($line);
 }
 $filename='office_list_';
 $this->CSV->render($filename);
 