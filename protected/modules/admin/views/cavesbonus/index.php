<?php /* @var $this Controller */ ?>
<?php /* @var Cavesbonus[] $coordinates */ ?>

<?php //echo '<pre>' . print_r($coordinates, 1) . '</pre>'; ?>



<table class="labyrinthScheme">
    <? $currentPointY = null; ?>
    <? foreach ($coordinates as $point): ?>
        <? $styleMarked = $point['marked'] ? ' marked' : '' ?>
        <?if($currentPointY !== $point['y']):?>
        <? $currentPointY = $point['y']; ?>
        <tr>
        <?endif?>
            <td<?= $point['move'] ? ' class="access' . $styleMarked . '" data-toggle="modal" data-target="#myModal" href="' . $this->createUrl('cavesbonus/getbonusbyajax', array('pointId' => $point['coordinate_id'])) . '"' : ' class="deny"' ?> id="pointId-<?=$point['coordinate_id']?>"></td>
        <?if($currentPointY !== $point['y']):?>
        </tr>
        <?endif?>

    <? endforeach ?>
</table>


<!-- Modal -->
<div id="ajax-modal" class="modal fade" tabindex="-1" style="display: none;"></div>
<!-- /.modal -->