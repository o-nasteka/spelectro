<div class="box b1">
    <div class="box-top"><?php echo BOX_HEADING_SEARCH; ?></div>
    <div class="box-cntd box-form">
		<?php echo tep_draw_form( 'search', tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false ), 'get', 'onsubmit="return fnCheckAdvanceSearch(this);"' ); ?>
            <?php echo tep_draw_input_field('buscaaar'.$languages_id, '', 'onfocus="this.value=\'\';" value="Buscar..." size="10" maxlength="30"'); ?>
            <button type="submit" id="btn-srch" title="Búsqueda Rápida" alt="Búsqueda Rápida" value="<?php echo ENTRY_SEARCH; ?>"></button>
        </form>
        <div>
            <?php echo BOX_SEARCH_TEXT . '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"> <strong>' . BOX_SEARCH_ADVANCED_SEARCH . '</strong></a>'; ?>
        </div>
    </div>
    <div class="box-fotr"></div>
</div>