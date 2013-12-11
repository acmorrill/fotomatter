<?php 
class FullSystemGroupTest extends TestSuite { 
  var $label = 'Full System Test'; 
  function FullSystemGroupTest() { 
    TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES); 
  } 
} 
?> 