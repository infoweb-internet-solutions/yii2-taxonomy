
var openTreeItems = $.cookie('infoweb-taxonomy-open-tree-items');

if (typeof openTreeItems === 'undefined') {
    openTreeItems = [];
} else {
    openTreeItems = JSON.parse(openTreeItems);
}

$(document).on('ready pjax:success', function() {

    /**
     * Save / remove cookie on collapse / expand
     */

    $(document).on('click', '[data-action=collapse], [data-action=expand]', function() {

        // Get action
        var action = $(this).data('action');

        // Get term id
        var term_id = $(this).parent().data('term');

        if (action == 'collapse') {

            if (!_.contains(openTreeItems, term_id)) {
                openTreeItems.push(term_id);
            }

        } else {

            openTreeItems = _.without(openTreeItems, term_id);
            //console.log(openTreeItems);
        }

        // Create cookie
        $.cookie('infoweb-taxonomy-open-tree-items', JSON.stringify(openTreeItems), { expires: 7, path: '/'});

    });

    /**
     * Active toggle
     * @todo Move to cms, or remove
     */
    $(document).on('click', '[data-toggle-active-term]', function(e){

        e.preventDefault();

        var id = $(this).data('toggle-active-term');

        $.ajax({
            url: 'active',
            type: 'POST',
            data: {'id': id},
            dataType: "json",
            success: function(data) {

                if (data.status == 1)
                {
                    $.pjax.reload({container: '#pjax-container'});
                } else {

                }
            }
        });
    });

    /**
     * Nested sortable
     */
    $('#sortable').nestable({

        expandBtnHTML: '<button data-action="collapse" class="collapsible btn fa-fw fa fa-chevron-down"></button>',
        collapseBtnHTML: '<button data-action="expand" class="expand btn fa-fw fa fa-chevron-right"></button>',
        cookieName: 'infoweb-taxonomy-open-tree-items',
        loadFromCookie: true,
        callback: function(l,e) {
            // l is the main container
            // e is the element that was moved

            // @todo Improve this code

            // Initialize vars
            var term = $(e).data('term'),
                level = $(e).closest('ol').data('level'),
                parent = '',
                parentOl = $(e).closest('ol'),
                direction = 'after';

            // Get the parent node
            // Siblings found, use previous 'li'
            if ($(e).siblings().length) {
                parent = $(e).prev().data('term');
            // No siblings, use parent and find the closest (previous) 'li'
            } else {
                parent = $(e).parent().closest('li').data('term');
            }

            // If the node is the first sibling, we add it before the next sibling
            if (typeof parent === 'undefined') {
                parent = $(e).next().data('term');
                direction = 'before';
            }

            // Set data-level for new ol
            if (typeof level === 'undefined') {
                // Get the level of the previous ol and assign it to the new ol
                var previousParentOl = parentOl.parent().closest('ol'),
                    previousParentLevel = previousParentOl.data('level');

                parentOl.data('level', previousParentLevel + 1);

                // Change direction
                direction = 'first';
            }

            /*
            console.log('term: ' + term);
            console.log('parent: ' + parent);
            console.log('level: ' + level);
            console.log('direction: ' + direction);
            */

            // Post the data
            $.ajax({
                url: 'sort',
                type: 'POST',
                data: {
                    term : term,
                    parent : parent,
                    direction: direction
                },
                dataType: "json",
                success: function(data) {

                    if (data.status == 1)
                    {
                        // @todo Add message
                        //console.log('success');
                        $.pjax.reload({container: '#pjax-container'});


                    } else {
                        // @todo Add message
                        //console.log('fail');
                    }
                }
            });

        }
    });


});