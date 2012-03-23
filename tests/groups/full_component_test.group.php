<?php 
class FullComponentGroupTest extends TestSuite { 
  var $label = 'Full Component Test'; 
  function FullComponentGroupTest() { 
    TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'components'); 
  } 
} 
?> 