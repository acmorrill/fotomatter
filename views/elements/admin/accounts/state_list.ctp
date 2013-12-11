<?php $states = $this->Account->get_states_by_country_code($country_code); ?>
<?php foreach ($states as $state): ?>
<option <?php echo isset($selected)&&$selected==$state['GlobalCountryState']['state_code_3']?'SELECTED':''; ?> value="<?php echo $state['GlobalCountryState']['state_code_3']; ?>"><?php echo $state['GlobalCountryState']['state_name']; ?></option>
<?php endforeach; ?>
