<?php 
/*
package: cmc_hook
file: admin/page.php 
*/

if(!defined('ABSPATH')) { 
    header('HTTP/1.0 403 Forbidden');
    exit;
}
if(	!cmc_hook::is_user_allowed()){
	exit('You do not have permission to view this page');
}

$menu = array(
    'hook'=>array('text'=>__('Hooks', 'cmchk'), 'href'=>'?page=cmc-hook', 'page'=>function(){    
        $sections = array(
            'hooks'=>array('page'=>function(){ 
				cmc_hook::$hooks->cmc_admin_view();
            }),    
        ); 
        $sections = apply_filters('cmchk_admin_page_section', $sections);
        $selected = empty($_REQUEST['section'])? 'hooks':$_REQUEST['section'];
        $sec_page = $sections[$selected];
        call_user_func_array( $sec_page['page'], array() );
    }),    
    'project'=>array('text'=>__('Project', 'cmchk'), 'href'=>'?page=cmc-hook&tab=project', 'page'=>function(){
        $sections = array(
            'project'=>array('page'=> function(){
                echo "<div id='cmchk_section_project_editor' class='cmchk_section'>";
                require("sections/project.php");
                echo "<div>";
            }),
            'projects'=>array('page'=>function(){  
                cmc_hook::$projects->cmc_admin_view();
            }),
        );
        $sections = apply_filters('cmchk_admin_page_section', $sections);
        $selected = empty($_REQUEST['section'])? 'projects':$_REQUEST['section'];
        $sec_page = $sections[$selected];
        call_user_func_array( $sec_page['page'], array() );
    }),
	'explorer'=>array('text'=>__('Explorer', 'cmchk'), 'href'=>'?page=cmc-hook&tab=explorer', 'page'=>function(){
		$sections = array(
            'explorer'=>array('page'=> function(){
                echo "<div id='cmchk_section_explorer' class='cmchk_section'>";
				require("sections/hook_editor.php");  
				echo "<div>";
            }),
        );
        $sections = apply_filters('cmchk_admin_page_section', $sections);
        $selected = empty($_REQUEST['section'])? 'explorer':$_REQUEST['section'];
        $sec_page = $sections[$selected];
        call_user_func_array( $sec_page['page'], array() );
	}),
    'settings'=>array('text'=>__('Settings', 'cmchk'), 'href'=>'?page=cmc-hook&tab=settings', 'page'=>function(){
		$sections = array(
            'settings'=>array('page'=> function(){
                echo "<div id='cmchk_section_settings' class='cmchk_section'>";
				require("sections/settings.php");  
				echo "<div>";
            }),
        );
        $sections = apply_filters('cmchk_admin_page_section', $sections);
        $selected = empty($_REQUEST['section'])? 'settings':$_REQUEST['section'];
        $sec_page = $sections[$selected];
        call_user_func_array( $sec_page['page'], array() );
        
    }),
);

$sel_tab = empty($_REQUEST['tab']) ? 'hook': $_REQUEST['tab'];

?>

<div class="wrap">
    <h1>
        <?php echo __('CMC Hooks', 'cmchk'); ?>
        <button id="cmchk-hook-add-form-btn" class="page-title-action cmchk-help-tip" type="button" data-tip="Add New Hook" onclick=" jQuery('#cmchk-hook-add-form').slideToggle('fast').find(':text').focus(); " >
            <?php echo __('Add Hook', 'cmchk');  ?>
        </button>
        <button id="cmchk-project-add-form-btn" class="page-title-action cmchk-help-tip" type="button"  onclick="jQuery('#cmchk-project-add-form').slideToggle('fast').find(':text').focus();">
            <?php echo __('Add Project', 'cmchk');  ?>
        </button>
        <button type="button" id="cmchk-form-project-impport-btn" class="page-title-action cmchk-help-tip" data-tip="Import Projects" onclick="jQuery('form#cmchk-form-project-import').slideToggle('fast').find(':file').focus();" >                
            <?php echo __('Import', 'cmchk'); ?>
        </button>  
		<?php $nonce = wp_create_nonce( 'cmchk-project-export-nonce' ); ?>
		<a href="?page=cmc-hook&cmchk_action=export&XDEBUG_SESSION_START&_wpnonce=<?php echo $nonce; ?>&id=all" id="cmchk-form-project-export-btn" class="page-title-action cmchk-help-tip" data-tip="Export All Projects" target="_blank" >                
            <?php echo __('Export', 'cmchk'); ?>
        </a>  		
    </h1>
    <div style="width:400px;">
        <form id="cmchk-form-project-import" method="post" enctype="multipart/form-data" class="" style="display:none;" action="?page=cmc-hook&cmchk_page=project" >
            <p>
                <?php wp_nonce_field( 'cmc-hook-import-nonce','_wpnonce', true, true ); ?>
                <input name="cmchk_action" type="hidden" value="import" />
                <input name="XDEBUG_SESSION_START" type="hidden" /><label>File: </label>
                <input type="file" name="cmchk_file_import" />
                <button type="submit" class="button button-primary" style="width:15%;"><?php echo __('Import', 'cmchk'); ?></button>
            </p>
        </form>
        <form id="cmchk-hook-add-form" class="cmchk-hook-project-add-form" style="display:none;" action="<?php echo admin_url('admin-ajax.php').'?action=cmchk_hook_editor&tab='.$_REQUEST['tab']; ?>" >
            <p>
                <?php wp_nonce_field( 'cmc-hook-nonce','_wpnonce', true, true ); ?>
                <input name="XDEBUG_SESSION_START" type="hidden" />
                <input type="text" name="title" class="widefat" style="width:70%" placeholder="<?php echo __("Hook Title", "cmchk"); ?>" />
                <button type="submit" class="button button-primary" style="width:15%;"><?php echo __('Save', 'cmchk'); ?></button>
            </p>
        </form>
        <form id="cmchk-project-add-form" class="cmchk-hook-project-add-form" style="display:none;" action="<?php echo admin_url('admin-ajax.php').'?action=cmchk_project_editor&tab='.$_REQUEST['tab']; ?>">
            <p>
                <?php wp_nonce_field( 'cmc-hook-project-nonce','_wpnonce', true, true ); ?>
                <input name="XDEBUG_SESSION_START" type="hidden" /> 
                <input type="text" name="title" class="widefat" style="width:70%" placeholder="<?php echo __("Project Title", "cmchk"); ?>" />
                <button  type="submit" class="button button-primary" style="width:15%;" ><?php echo __('Save', 'cmchk'); ?></button>
            </p>
        </form>
    </div>          
    <h2 id="cmchk_tab_menu" class="nav-tab-wrapper wp-clearfix">        
        <?php             
            $menu = apply_filters('cmchk_admin_page_menu', $menu);
            foreach($menu as $k => $m){
                if( $m['active'] === false) continue;
                $s = ($sel_tab == $k)? "nav-tab-active":""; $m['class'] = is_array($m['class'])? implode(' ', $m['class']):$m['class'];
                echo sprintf('<a href="%s" class="nav-tab %s %s" %s > %s </a>', $m['href'], $m['class'], $s, $m['atts'], $m['text'] );
            }
        ?> 
    </h2>
    <div id="cmchk_tab" class="cmchk_tab_<?php echo $sel_tab; ?>"> 
        <?php
            $page = $menu[$sel_tab];
            call_user_func_array( $page['page'], array() );
        ?>
    </div>
</div>
<script>
	var cmchk = cmchk || {};
	function cmchk_page_load( $wrap ){
		if( !$wrap )return;
		var tiptip_args = {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		};	
	
		$wrap.find( '.cmchk-help-tip' ).tipTip( tiptip_args ).css( 'cursor', 'help' );
	}

    (function($, cmchk){
		cmchk_page_load( $(document) );
        $('.cmchk-hook-project-add-form :submit').click(function(){ 
            var $btn = $(this), $form = $btn.closest('form'); 
            $btn.prop('disabled', true); var data = $form.serializeArray();
            if( $form.is('#cmchk-hook-add-form')) data.push({name: 'project_id', value: $('#cmchk_project_id').val() || 0});
            $.post($form.attr('action'), data, function(data){
                if( data.url )document.location = data.url;
                if(data.message) alert(data.message);                                
            }).always(function(){
                $btn.prop('disabled', false);
            }).fail(function(){
                alert('Network Error: Unable To add Hook');
            });
            return false;
        });
		
    })(jQuery, cmchk);
	
</script>
