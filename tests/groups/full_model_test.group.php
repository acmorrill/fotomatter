<?php 
class FullModelGroupTest extends TestSuite { 
  var $label = 'Full Model Test'; 
  function FullModelGroupTest() { 
    TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'models'); 
  } 
} 
?> 