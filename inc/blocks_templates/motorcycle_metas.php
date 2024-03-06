<div  <?= get_block_wrapper_attributes([
  'class' => ($is_grid) ? " moto-data-grid-display moto-grid-$attributes[columns]" : ' moto-data-table-display wp-block-table'
]) ?>>

  <?php 
  $motorcycle_metas = get_motorcycle_metas_array();
  
  if(!$is_grid): ?>
    <table>
      <tbody>
  <?php endif; ?>
  <?php foreach ($attributes['visible_metas'] as $meta_name) :
    if ($is_grid) : ?>
      <div class="moto-data-item">
        <h5 class="components-truncate components-text components-heading">
          <?= $motorcycle_metas[$meta_name][0] ?>
        </h5>
        <span>
          <?= get_motorcycle_metas($meta_name) ?>
        </span>
      </div>
      <?php else : ?>
        <tr>
          <th class="moto-data-item">
            <h5><?= $motorcycle_metas[$meta_name][0] ?></h5>
          </th>
        <td class="moto-data-item">
          <span>
            <?= get_motorcycle_metas($meta_name) ?>
          </span>
        </td>
      </tr>
    <?php endif; 
  endforeach; ?>
  <?php if(!$is_grid): ?>
      </tbody>
    </table>
  <?php endif; ?>

</div>