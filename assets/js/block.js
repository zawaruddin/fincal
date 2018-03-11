function blockTab(blc){
	blc.block({
        overlayCSS: {
            backgroundColor: '#CCF3FF',
            cursor: 'progress'
        },
        message:  '<div class="loading-spinner" style="width: 200px; margin-left: -100px;"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div>',
        css: {
            border: 'none',
            color: '#4D79FF',
            background: 'none',
            cursor: 'progress'
        }
    });
}
	
function unblockTab(blc){
	window.setTimeout(function () {
		blc.unblock();
	}, 1000);
}