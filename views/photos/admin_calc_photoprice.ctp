<h1><?php echo $data['OldPhoto']['displayTitle']; ?></h1>
<?php $showRangeValues = false; ?>
<?php $showOnly125 = false; ?>
<table cellpadding="10">
     <tr>
          <th>Size:</th>
          <th>Edition of:</th>
          <th>Up Per:</th>
          <th>Supergloss:</th>
          <th>Aluma Print:</th>
          <th>25:</th>
          <th>50:</th>
          <th>100:</th>
          <th>125:</th>
          <?php if ($showOnly125 == false): ?>
          <th>175:</th>
          <th>250:</th>
          <?php endif; ?>
     </tr>
<?php foreach ($prices as $price): ?>
     <tr>
          <td><?php echo $price['textSize']; ?></td>
          <td><?php echo $price['editionOf']; ?></td>
          <td><?php echo $price['goUpPer']; ?></td>
          <td><?php echo $price['price']; ?></td>
          <td><?php echo $price['alumPrice']; ?></td>
          <td><?php echo $price['alum_print_25_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['alum_print_25_high']; ?><?php endif; ?> <br/><br/><?php echo $price['totals_25_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['totals_25_high']; ?><?php endif; ?></td>
          <td><?php echo $price['alum_print_50_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['alum_print_50_high']; ?><?php endif; ?> <br/><br/><?php echo $price['totals_50_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['totals_50_high']; ?><?php endif; ?></td>
          <td><?php echo $price['alum_print_100_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['alum_print_100_high']; ?><?php endif; ?> <br/><br/><?php echo $price['totals_100_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['totals_100_high']; ?><?php endif; ?></td>
          <td><?php echo $price['alum_print_125_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['alum_print_125_high']; ?><?php endif; ?> <br/><br/><?php echo $price['totals_125_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['totals_125_high']; ?><?php endif; ?></td>
          <?php if ($showOnly125 == false): ?>
          <td><?php echo $price['alum_print_175_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['alum_print_175_high']; ?><?php endif; ?> <br/><br/><?php echo $price['totals_175_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['totals_175_high']; ?><?php endif; ?></td>
          <td><?php echo $price['alum_print_250_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['alum_print_250_high']; ?><?php endif; ?> <br/><br/><?php echo $price['totals_250_low']; ?><?php if ($showRangeValues): ?> - <?php echo $price['totals_250_high']; ?><?php endif; ?></td>
          <?php endif; ?>
     </tr>
<?php endforeach; ?>
</table>
