<?php
class AccountHelper extends AppHelper {
    public function get_states_by_country_code($country_code) {
        return ClassRegistry::init('GlobalCountryState')->get_states_by_country_code($country_code);
    }
}