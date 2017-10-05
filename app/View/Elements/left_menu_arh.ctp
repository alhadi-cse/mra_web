
<div id="accordian">
    <?php echo $this->requestAction(array('controller' => 'AdminModuleModules', 'action' => 'module'), array('return')); ?>
</div>


<script type="text/javascript">

    $(document).ready(function () {

        $('#accordian a').click(function () {
            var link = $(this);
            var closest_li = link.closest('li');
            var link_status = closest_li.hasClass('active');

            if (!closest_li.children("ul").length) {
                if (link_status)
                    return;
                else {
                    var siblings_active_links = closest_li.parents("ul").last().find('.active:not(:has(ul))');
                    siblings_active_links.removeClass('active');
                    closest_li.addClass('active');
                    return;
                }
            }

            var closest_ul = link.closest('ul');
            var all_active_links = closest_ul.find('.active:has(> ul)');

            closest_ul.find('ul').slideUp(500);
            all_active_links.removeClass('active');

            if (!link_status) {
                closest_li.children('ul').slideDown(500);
                closest_li.addClass('active');
            }
            return false;
        });

    });

    function SelectionChenge() {
        this.prev = this.previous = function () {
            var active_link = $('#accordian').find('li.active:not(:has(ul))');
            if (active_link.length) {
                var prev_link = active_link.prev();
                if (prev_link.length) {
                    var active_parent_ul = active_link.parents('ul').first();
                    if (active_parent_ul.length) {
                        if (active_parent_ul.css('display') === 'none') {
                            active_link.parents("ul").last().find('ul').slideUp(300, function () {
                                active_link.parents("ul").last().find('.active').removeClass('active');
                                active_link.parents("ul").slideDown(500, function () {
                                    active_link.parents("li").addClass('active');
                                });
                            });
                        } else
                            active_link.removeClass('active');

                        prev_link.addClass('active');
                        var actPos = (active_link.index() - 2) * active_link.outerHeight();
                        actPos = (actPos < 0) ? 0 : actPos;
                        if (actPos < active_parent_ul.scrollTop())
                            active_parent_ul.animate({scrollTop: actPos}, 500);
                    }
                } else {
                    var active_parent_li = active_link.parents('li').first();
                    if (active_parent_li.length && active_parent_li.prev() && active_parent_li.prev().find('li:not(:has(ul))').first()) {
                        prev_link = active_parent_li.prev().find('li:not(:has(ul))').last();
                        var prev_parent_ul = prev_link.parents('ul').first();
                        if (prev_parent_ul.length) {
                            if (prev_parent_ul.css('display') === 'none') {
                                active_link.parents("ul").first().slideUp(500, function () {
                                    active_parent_li.find('.active').removeClass('active');
                                    active_parent_li.removeClass('active');
                                    prev_link.parents("li").addClass('active');
                                    prev_link.parents("ul").slideDown(500, function () {
                                        prev_parent_ul.animate({scrollTop: prev_parent_ul.prop("scrollHeight")}, 200, function () {
                                            prev_link.addClass('active');
                                        });
                                    });
                                });
                            } else
                                active_link.removeClass('active');
                        }
                    }
                }
            }
        };

        this.next = function () {
            var active_link = $('#accordian').find('li.active:not(:has(ul))');
            if (active_link.length) {
                var next_link = active_link.next();
                if (next_link.length) {
                    var active_parent_ul = active_link.parents('ul').first();
                    if (active_parent_ul.length) {
                        if (active_parent_ul.css('display') === 'none') {
                            active_link.parents("ul").last().find('ul').slideUp(300, function () {
                                active_link.parents("ul").last().find('.active').removeClass('active');
                                active_link.parents("ul").slideDown(500, function () {
                                    active_link.parents("li").addClass('active');
                                });
                            });
                        } else
                            active_link.removeClass('active');

                        next_link.addClass('active');
                        if (active_link.index() > 2) {
                            var actPos = (active_link.index() - 2) * active_link.outerHeight();
                            if (actPos > active_parent_ul.scrollTop())
                                active_parent_ul.animate({scrollTop: actPos}, 500);
                        }
                    }
                } else {
                    var active_parent_li = active_link.parents('li').first();
                    if (active_parent_li.length && active_parent_li.next() && active_parent_li.next().find('li:not(:has(ul))').first()) {
                        next_link = active_parent_li.next().find('li:not(:has(ul))').first();
                        var next_parent_ul = next_link.parents('ul').first();
                        if (next_parent_ul.length) {
                            if (next_parent_ul.css('display') === 'none') {
                                active_link.parents("ul").first().slideUp(500, function () {
                                    active_parent_li.find('.active').removeClass('active');
                                    active_parent_li.removeClass('active');
                                    next_link.parents("li").addClass('active');
                                    next_link.parents("ul").slideDown(500, function () {
                                        next_parent_ul.animate({scrollTop: 0}, 200, function () {
                                            next_link.addClass('active');
                                        });
                                    });
                                });
                            } else
                                active_link.removeClass('active');
                        }
                    }
                }
            }
        };
    }
    var msc = new SelectionChenge();

</script>
