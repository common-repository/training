/* 
 author: rudrainnovatives.com
 */

$ = jQuery;
var progInterval;
clearInterval(progInterval);

var modadded = 0;
var formjson = {
  fields: [
    {
      label: "Share Youe Experience?",
      field_type: "text",
      required: false,
      field_options: {},
      cid: "c1",
    },
    {
      label: "How was the seminar?",
      field_type: "radio",
      required: true,
      field_options: {
        options: [
          {
            label: "Fine",
            checked: false,
          },
          {
            label: "Good",
            checked: false,
          },
          {
            label: "Not Good",
            checked: false,
          },
          {
            label: "Bad",
            checked: false,
          },
        ],
        include_other_option: true,
      },
      cid: "c10",
    },
  ],
  fields: [
    {
      label: "Share Youe Experience?",
      field_type: "text",
      required: false,
      field_options: {},
      cid: "c1",
    },
    {
      label: "How was the seminar?",
      field_type: "radio",
      required: true,
      field_options: {
        options: [
          {
            label: "Fine",
            checked: false,
          },
          {
            label: "Good",
            checked: false,
          },
          {
            label: "Not Good",
            checked: false,
          },
          {
            label: "Bad",
            checked: false,
          },
        ],
        include_other_option: true,
      },
      cid: "c10",
    },
  ],
};

var dataofform = JSON.stringify(formjson);

jQuery(function ($) {
  // edit category info
  jQuery(document).on("click", ".edit-category-info", function () {
    jQuery("#updateCategoryTr").show();
    var category_info = jQuery(this).prev("span.category-txt").text();
    jQuery("#updateCategoryTr #txtUpdateName").val(category_info);
    jQuery("#updateCategoryTr input[name='category_id']").val(
      jQuery(this).prev("span.category-txt").attr("data-id")
    );
    jQuery("#updateCategoryTr").show();
  });

  // update category info details by category modal
  jQuery("#frmUpdateCatgeory").validate({
    submitHandler: function () {
      var postdata =
        jQuery("#frmUpdateCatgeory").serialize() +
        "&action=training_lib&param=update_category_info"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          show_msg(data.sts, data.msg);
          jQuery("#updateCategoryTr").hide();
          setTimeout(function () {
            location.reload();
          }, 1200);
        } else {
          show_msg(data.sts, data.msg);
        }
      });
    },
  });

  // open subcategory modal
  jQuery(document).on("click", ".edit-subcategory-info", function () {
    jQuery("#updateSubcategoryTr").show();
    var category_id = jQuery(this).attr("data-id");
    jQuery("#updateSubcategoryTr #category_list_id").val(category_id);
    var postdata =
      "action=training_lib&param=show_subcategory_list&category_id=" +
      category_id+"&nonce=" + rtr_script_data.nonce;
    jQuery.post(ajaxurl, postdata, function (response) {
      var data = jQuery.parseJSON(response);
      if (data.arr.subcategory.length > 0) {
        var rowWiseSubcategory = "";
        var count = 1;
        jQuery.each(data.arr.subcategory, function (index, item) {
          rowWiseSubcategory +=
            "<tr><td>" +
            count +
            "</td><td>" +
            item +
            "</td><td><button type='button' class='rtr-btn rtr-btn-primary btneditlistitem'>Edit</button><button type='button' class='rtr-btn rtr-btn-success btnupdatelistitem' style='display:none' data-value='" +
            item +
            "'>Update</button></td></tr>";
          count++;
        });
        jQuery("#subcategory-list").html(rowWiseSubcategory);
        jQuery("#updateSubcategoryTr").show();
      } else {
        show_msg(0, "No Subcategory found");
      }
    });
  });

  // editing subcategory item from list
  jQuery(document).on("click", ".btneditlistitem", function () {
    var current_value = jQuery(this).parent().prev("td").text();
    jQuery(this).css("display", "none");
    jQuery(this)
      .parent()
      .find("button.btnupdatelistitem")
      .css("display", "block");
    jQuery(this)
      .parent()
      .prev("td")
      .html("<input type='text' value='" + current_value + "'/>");
  });

  jQuery(document).on("click", ".btnupdatelistitem", function () {
    var updated_value = jQuery(this).parent().prev("td").find("input").val();
    var old_value = jQuery(this).attr("data-value");
    var category_id = jQuery("#category_list_id").val();
    var postdata =
      "action=training_lib&param=update_subcategory_item&updated_value=" +
      updated_value +
      "&old_value=" +
      old_value +
      "&category_id=" +
      category_id+"&nonce=" + rtr_script_data.nonce;
    jQuery.post(ajaxurl, postdata, function (response) {
      var data = jQuery.parseJSON(response);
      if (data.sts) {
        show_msg(data.sts, data.msg);
        jQuery("#updateSubcategoryTr").hide();
        setTimeout(function () {
          location.reload();
        }, 1200);
      } else {
        show_msg(data.sts, data.msg);
      }
    });
  });

  jQuery(document).on("click", ".delete-link", function () {
    var conf = confirm("Are you sure want to delete?");
    if (conf) {
      var val = jQuery(this).attr("data-link");
      var resource_id = jQuery(this).attr("data-res");
      var postdata =
        "action=training_lib&param=delete_project_links&link=" +
        val +
        "&res=" +
        resource_id+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          jQuery(".mypercentage").html(data.arr.percent + " % Complete");

          jQuery(".perdiv").attr(
            "style",
            "width:" + parseInt(data.arr.percent, 10) + "%"
          );

          toggleModal("#subPrjModal");
          if (data.arr.visible == 1) {
            jQuery("a.resource_" + resource_id)
              .text("Submit Project")
              .attr("data-status", "unmarked")
              .parent()
              .parent("div#resource_" + resource_id)
              .removeClass("markeddiv")
              .addClass("unmarkeddiv");
          }
        }
      });
    }
  });

  jQuery(document).on("click", ".delete-media", function () {
    var conf = confirm("Are you sure want to delete?");
    if (conf) {
      var val = jQuery(this).attr("data-link");
      var resource_id = jQuery(this).attr("data-res");
      var postdata =
        "action=training_lib&param=delete_project_media&media=" +
        val +
        "&res=" +
        resource_id+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          jQuery(".mypercentage").html(data.arr.percent + " % Complete");
          jQuery(".perdiv").attr(
            "style",
            "width:" + parseInt(data.arr.percent, 10) + "%"
          );
          toggleModal("#subPrjModal");
          if (data.arr.visible == 1) {
            jQuery("a.resource_" + resource_id)
              .text("Submit Project")
              .attr("data-status", "unmarked")
              .parent()
              .parent("div#resource_" + resource_id)
              .removeClass("markeddiv")
              .addClass("unmarkeddiv");
          }
        }
      });
    }
  });

  var allCheckedBoxesFrontEnd = [];

  //remove on bade click
  jQuery(document).on("click", ".filter-list", function () {
    var val = jQuery(this).attr("data-value");
    jQuery(".training-cat input.cr-subcat[value='" + val + "']").click();
    jQuery(this).remove();
  });
  //strike thru message on mouse hover
  jQuery(document).on("mouseover", ".pl-badge", function () {
    jQuery(this).parent().addClass("remove-pl-badge");
  });
  //remove strike thru on mouse out
  jQuery(document).on("mouseout", ".pl-badge", function () {
    jQuery(this).parent().removeClass("remove-pl-badge");
  });

  jQuery(document).on("click", "input.cr-subcat", function () {
    var category_id = jQuery(this)
      .closest("div.parent-div-chk")
      .attr("data-id");

    if (this.checked) {
      allCheckedBoxesFrontEnd.push(jQuery(this).val());

      var vl = jQuery(this).val();
      var clname = vl.replace(/ /g, "_").toLowerCase();

      if (jQuery(".filter-list").length > 0) {
        jQuery("#filter-div").append(
          '<div class="rmv_' +
            clname +
            '"><a class="filter-list" data-value="' +
            vl +
            '">' +
            jQuery(this).val() +
            ' <span class="pl-badge" title="remove" ><i class="fa fa-close"></i></span></a></div>'
        );
      } else {
        jQuery("#filter-div").html(
          '<div class="rmv_' +
            clname +
            '"><a class="filter-list" data-value="' +
            vl +
            '">' +
            jQuery(this).val() +
            ' <span class="pl-badge" title="remove" ><i class="fa fa-close"></i></span></a></div>'
        );
      }

      jQuery("body").addClass("rtr-processing");
      var postdata =
        "action=training_lib&param=filter_course&subcat=" +
        allCheckedBoxesFrontEnd +
        "&category_id=" +
        category_id+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          jQuery(".mytab-course").html(data.arr.template);
          jQuery("body").removeClass("rtr-processing");
        } else {
          jQuery(".mytab-course").html(
            '<div class="rtr-alert rtr-alert-danger notfound"><p>' +
              data.msg +
              "</p></div>"
          );
          jQuery("body").removeClass("rtr-processing");
        }
      });
    } else {
      //allCheckedBoxesFrontEnd.pop(jQuery(this).val());
      var index = allCheckedBoxesFrontEnd.indexOf(jQuery(this).val());
      var vl = jQuery(this).val();
      var clname = vl.replace(/ /g, "_").toLowerCase();
      if (index > -1) {
        jQuery("#filter-div")
          .find(".rmv_" + clname)
          .remove();
        allCheckedBoxesFrontEnd.splice(index, 1);
        jQuery("body").addClass("rtr-processing");
        var postdata =
          "action=training_lib&param=filter_course&subcat=" +
          allCheckedBoxesFrontEnd +
          "&category_id=" +
          category_id+"&nonce=" + rtr_script_data.nonce;
        jQuery.post(ajaxurl, postdata, function (response) {
          var data = jQuery.parseJSON(response);
          jQuery("body").removeClass("rtr-processing");
          if (data.sts == 1) {
            jQuery(".mytab-course").html(data.arr.template);
          } else {
            jQuery(".mytab-course").html(
              '<div class="rtr-alert rtr-alert-danger notfound"><p>' +
                data.msg +
                "</p></div>"
            );
          }
        });
      }
    }
  });

  jQuery(document).on("click", "#allcourse-tab", function () {
    var postdata = "action=training_lib&param=load_all_courses"+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    location.reload();
  });

  jQuery(".wp-submenu li a").each(function (i, obj) {
    if (obj.text === "") {
      jQuery(this).closest("li").css("display", "none");
    }
  });

  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 273) {
      jQuery(".sidebar-left").addClass("fixedSidebar");
      var fixed_header_height = jQuery(".fixed_header").height();
      var nav = jQuery("#training-ui-container");
      if (nav.length) {
        var content_div_offset = nav.offset().top;
        var content_div_height = nav.height();
      }
      var total = content_div_offset + content_div_height;
      var scroll_mouse = jQuery(window).scrollTop() + fixed_header_height + 100;
      if (scroll_mouse > total) {
        jQuery(".sidebar-left").removeClass("fixedSidebar");
      }
    } else {
      jQuery(".sidebar-left").removeClass("fixedSidebar");
    }
  });

  // jQuery('[data-toggle="tooltip"]').tooltip();

  datatables();
  if (jQuery("#hidcontentrec").length == 0) {
    forms();
  }

  modalhide();
  genfuntions($);
  funs($);
  chosen_initilize();
  if (jQuery("#formid").length > 0 && jQuery("#formid").val() > 0) {
    var idmentor = jQuery("#creatementorform").val();
    if (idmentor > 0) {
      if ($.trim(jQuery(".savedjsondata").text()) != "") {
        var savedjson = $.trim(jQuery(".savedjsondata").text());
        dataofform = savedjson;
      }
      loadform(idmentor, 1);
    }
  }
  if (jQuery("#hidcontentrec").length == 0) {
    jQuery(".accordion").accordion({
      collapsible: true,
    });
  }
  jQuery(".tabcustom a").click(function () {
    jQuery(this).tab("show");
  });

  if (jQuery(".singlepagecourse").length > 0) {
    jQuery(".perint").text(jQuery("#percent_bar").val() + " % Complete");
    jQuery(".perdiv").css("width", jQuery("#percent_bar").val() + "%");
    setTimeout(function () {
      jQuery(".subheader").hide();
      jQuery(".module").removeClass("active");
      jQuery(".sidebar-left ul:first-child li.module:first-child")
        .addClass("active")
        .addClass("parentli");
      jQuery(".modulelesson1").slideDown("slow");
    }, 300);
  }

  if (jQuery("#formid").val() > 0) {
    var form_id = jQuery("#formid").val();
    if ($.trim(jQuery(".savedjsondata").text()) != "") {
      var savedjson = $.trim(jQuery(".savedjsondata").text());
      dataofform = savedjson;
      savedjson = $.parseJSON(savedjson);
      formbuilder(form_id, savedjson.fields);
    } else {
      formbuilder(form_id, formjson.fields);
    }
  }

  var isclicked = 0;

  jQuery(".sidebar-left")
    .find("a")
    .click(function () {
      isclicked = 1;
      if (jQuery(this).parent().hasClass("active")) {
        return false;
      }

      jQuery(".active").removeClass("active");
      jQuery(".parentli").removeClass("parentli");
      if (jQuery(this).parent().hasClass("module")) {
        jQuery(this).parent().addClass("parentli");

        if (!jQuery(this).parent().parent().next(".subheader").is(":visible")) {
          jQuery(".subheader").slideUp("slow");
          jQuery(this).parent().parent().next(".subheader").slideDown("slow");
        }
      }

      jQuery(this).parent().addClass("active");
      var sel = this;
      var hash = jQuery(sel).attr("href");

      var newTop = parseInt(
        jQuery(hash).offset().top - jQuery(".content_header").height()
      );
      var old_top = newTop;
      if (jQuery("div.hor-menu-full").length > 0) {
        var headht = jQuery("div.hor-menu-full .navbar-nav-full").height();
        newTop = newTop - headht;
      }

      jQuery("html,body")
        .stop()
        .animate({ scrollTop: newTop }, 300, function () {
          //window.location.hash = hash; - sZap1n#jaAQylKIuNmwAtr
        });

      setTimeout(function () {
        isclicked = 0;
      }, 1500);

      return false;
    });

  var i = 0;
  var lastScrollTop = 0;
  jQuery(window).scroll(function () {
    if (
      jQuery(".templatemain").length > 0 &&
      jQuery(".singlepagecourse").length > 0
    ) {
      if (jQuery("div.hor-menu-full").length > 0) {
        var mched = jQuery("div.hor-menu-full").get(0).getBoundingClientRect();
        var headht = jQuery("div.hor-menu-full").height();
        var mcctop = Math.abs(mched.top);
        if (mcctop < headht) {
          jQuery("div.hor-menu-full .navbar-nav-full").removeAttr("style");
        } else {
          jQuery("div.hor-menu-full .navbar-nav-full").css({
            top: "0",
            "z-index": "999999",
            position: "fixed",
            background: "#444D58",
          });
        }
      }

      if (jQuery("#contentheader").length > 0) {
        if (jQuery("#contentheader").visible(true)) {
          jQuery(".fixed_header")
            .removeAttr("style")
            .css({ visibility: "hidden", display: "none" });
        } else {
          var toptrainign = 0;
          if (jQuery("div.hor-menu-full").length > 0) {
            var headht = jQuery("div.hor-menu-full .navbar-nav-full").height();
            toptrainign = headht;
          }

          jQuery(".fixed_header")
            .css({
              top: toptrainign,
              visibility: "visible",
              display: "block",
              position: "fixed",
              "z-index": "99999",
            })
            .show();
          if (jQuery("#wpadminbar").length > 0) {
            jQuery(".scroll-class-left").addClass("fixleftcoltop_left_admin");
            jQuery(".scroll-class-right").addClass(
              "fixrightcoltop_right_admin"
            );
          } else {
            jQuery(".scroll-class-left").addClass(
              "fixleftcoltop_without_admin"
            );
            jQuery(".scroll-class-right").addClass(
              "fixrightcoltop_without_admin"
            );
          }
        }
      }

      if (isclicked == 0) {
        var paridmod = jQuery(".parentli").attr("id");
        var parattrmod = jQuery(".parentli").attr("data-attr");

        var ob = jQuery(this);
        var st = ob.scrollTop();
        jQuery(".innerdata .blockcontent").each(function () {
          var id = jQuery(this).attr("id");
          var el = document.getElementById(id).getBoundingClientRect();
          var top = el.top;
          if (top <= 200 && top >= 60) {
            var obj = jQuery(".sidebar-left a[href='#" + id + "']");
            if (obj.parent().hasClass("module")) {
              if (!obj.parent().hasClass("parentli")) {
                if (jQuery("#" + paridmod).hasClass("parentli")) {
                  jQuery("#" + paridmod)
                    .parent()
                    .next(".subheader")
                    .slideUp("slow");
                }

                jQuery(".parentli").removeClass("parentli");
                obj.parent().addClass("parentli");

                obj.parent().parent().next(".subheader").slideDown("slow");
              }
            } else {
              //scroll up
              if (st < lastScrollTop) {
                if (obj.parent().hasClass("leson")) {
                  var atr = obj.parent().attr("data-attr");

                  if (parattrmod != atr) {
                    if (jQuery("#" + paridmod).hasClass("parentli")) {
                      jQuery(".parentli").removeClass("parentli");
                      jQuery("#" + paridmod)
                        .parent()
                        .next(".subheader")
                        .slideUp("slow");
                      jQuery(".module[data-attr=" + atr + "]").addClass(
                        "parentli"
                      );
                      jQuery(".module[data-attr=" + atr + "]")
                        .parent()
                        .next(".subheader")
                        .slideDown("slow");
                    }
                  }
                }
              }
            }

            jQuery(".active").removeClass("active");
            obj.parent().addClass("active");
          }
        });

        lastScrollTop = st;
      }
    }
  });

  if (
    jQuery(".toplevel_page_triningtool,.toplevel_page_manage_mentor_calls")
      .length > 0
  ) {
    liset();
  }

  if (jQuery(".mentorcallpage").length > 0) {
    if (
      jQuery("#student_user").val() != "" &&
      jQuery("#student_user").val() > 0
    ) {
      jQuery("#datecall").focus();
    }
  }

  if (jQuery(".smallinfo").length > 0) {
    jQuery(".smallinfo").each(function () {
      jQuery(this).html(jQuery(this).html().replace("Â", " "));
    });
  }
});

function liset() {
  jQuery(
    ".toplevel_page_triningtool li a,.toplevel_page_manage_mentor_calls li a"
  ).each(function () {
    var text = $.trim(jQuery(this).text());
    if (text == "") {
      jQuery(this).css("padding", "0");
    }
  });
}

jQuery(window).bind("load", function () {
  if (jQuery(".singlepagecourse").length > 0) {
    setTimeout(function () {
      jQuery("body").scrollTop(0);
    }, 100);
  }
});

$(".defaultuploadimg").click(function (e) {
  e.preventDefault();
  var image = wp
    .media({
      title: "Upload Image",
      multiple: false,
    })
    .open()
    .on("select", function (e) {
      var uploaded_image = image.state().get("selection").first();
      var image_url = uploaded_image.toJSON().url;

      $(".uploadCourseImage").html(
        '<div class="editImg"><a class="editImgAction" href="javascript:void(0);""><i class="fa">&#xf00d;</i></a></div><img src="' +
          image_url +
          '">'
      );
      $(".defaultCourseImgUrl").val(image_url);
      $("body").addClass("modal-open");
    });
});

jQuery("#frm-default-image").validate({
  submitHandler: function () {
    var imageUrl = jQuery("input.defaultCourseImgUrl").val();
    if (imageUrl !== "") {
      var postdata =
        "default_image=" +
        imageUrl +
        "&param=default_course_image&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        var data = jQuery.parseJSON(response);
        show_msg(data.sts, data.msg);
      });
    } else {
      show_msg(0, "Choose default course image");
    }
  },
});

window.onscroll = function (ev) {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
    if (
      jQuery(".templatemain").length > 0 &&
      jQuery(".singlepagecourse").length > 0
    ) {
      setTimeout(function () {
        jQuery(".active").removeClass("active");
        if (jQuery(".lastproj").length > 0)
          jQuery(".module:last").addClass("active");
        else jQuery(".subheader li:last").addClass("active");
      }, 50);
    }
  }
};

function datatables() {
  jQuery("#data_courses").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [5] }],
  });

  jQuery("#data_categories").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [3] }],
  });

  jQuery("#data_assign").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[1, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [0, 4] }],
  });

  jQuery("#coursesimages").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [3] }],
  });

  jQuery("#data_modules").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [5] }],
  });

  jQuery("#data_lessons").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [5] }],
  });

  jQuery("#data_resources").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [5] }],
  });

  jQuery("#data_calls,#data_forms").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    //"aaSorting": [[ 2, "desc" ]],
    //"aoColumnDefs": [{ "bSortable": false, "aTargets": [5] }]
  });

  jQuery("#data_notes").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[2, "desc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [3] }],
  });

  jQuery("#data_links, #data_docs").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [2] }],
  });

  jQuery("#data_sresult").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[4, "desc"]],
    aoColumnDefs: [{ bSortable: false, aTargets: [5] }],
  });

  jQuery(".commontbl").dataTable({
    // "bJQueryUI": false,
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
  });
  tabldynmic();

  if (jQuery(".datetimepicker").length > 0) {
    jQuery(".datetimepicker").datetimepicker();
  }
}

function tabldynmic() {
  jQuery(".tblenrolled").dataTable({
    bAutoWidth: false,
    sPaginationType: "full_numbers",
    sDom: '<"datatable-header"fl>t<"datatable-footer"ip>',
    oLanguage: {
      sLengthMenu: "<span>Show entries:</span> _MENU_",
    },
    aaSorting: [[0, "asc"]],
    //"aoColumnDefs": [{ "bSortable": false, "aTargets": [3] }]
  });
}

function forms() {
  jQuery("#add_course").validate({
    ignore: ".hidfield",
    submitHandler: function () {
      var description = "";
      if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
        description = encodeURIComponent(
          tinyMCE.get("description").getContent()
        );
      } else {
        description = encodeURIComponent(jQuery("#description").val());
      }

      var isupdate = 0;
      var param = "&param=add_course&action=training_lib";
      if (jQuery("#course_id").length > 0 && jQuery("#course_id").val() > 0) {
        isupdate = 1;
      }

      var podata =
        jQuery("#add_course").serialize() +
        "&description=" +
        description +
        param+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(dat);

        if (data.sts == 0) {
          show_msg(data.sts, data.msg);
        } else {
          if (isupdate == 1) {
            show_msg(data.sts, data.msg);
          } else {
            var insertid = data.arr.lastid;
            location.href =
              "admin.php?page=rtr_course_detail&course_id=" + insertid;
          }
        }
      });
    },
  });

  jQuery("#addmodules").validate({
    submitHandler: function () {
      var description = "";
      if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
        description = encodeURIComponent(
          tinyMCE.get("description").getContent()
        );
      } else {
        description = encodeURIComponent(jQuery("#description").val());
      }
      var podata =
        jQuery("#addmodules").serialize() + "&description=" + description+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(
        ajaxurl,
        podata + "&param=add_module&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          if (jQuery("#id").val() == 0) {
            jQuery("#addmodules").get(0).reset();
            window.location.href = jQuery(".bkbtn").attr("href");
          } else {
            window.location.reload();
          }
        }
      );
    },
  });
  jQuery("#addlesson").validate({
    submitHandler: function () {
      var description = "";
      if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
        description = encodeURIComponent(
          tinyMCE.get("description").getContent()
        );
      } else {
        description = encodeURIComponent(jQuery("#description").val());
      }
      var podata =
        jQuery("#addlesson").serialize() + "&description=" + description+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(
        ajaxurl,
        podata + "&param=add_lesson&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);

          if (jQuery("#lessid").val() == 0) {
            jQuery("#addlesson").get(0).reset();
            window.location.href = jQuery(".bkbtn").attr("href");
          } else {
            window.location.reload();
          }
        }
      );
    },
  });

  jQuery("#addresource").validate({
    submitHandler: function () {
      var description = "";
      if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
        description = encodeURIComponent(
          tinyMCE.get("description").getContent()
        );
      } else {
        description = encodeURIComponent(jQuery("#description").val());
      }
      var podata =
        jQuery("#addresource").serialize() + "&description=" + description+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(
        ajaxurl,
        podata + "&param=add_resource&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          if (jQuery("#typerescreated").val() == "page") {
            jQuery("#addresource").get(0).reset();
          }
          if (jQuery("#resid").val() == 0)
            jQuery("#addresource").get(0).reset();

          modadded++;
        }
      );
    },
  });

  jQuery("#addprojectexce").validate({
    submitHandler: function () {
      var description = "";
      if (jQuery("#wp-description1-wrap").hasClass("tmce-active")) {
        description = encodeURIComponent(
          tinyMCE.get("description1").getContent()
        );
      } else {
        description = encodeURIComponent(jQuery("#description1").val());
      }

      var podata =
        jQuery("#addprojectexce").serialize() + "&description=" + description+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(
        ajaxurl,
        podata + "&param=add_projectexcersie&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
        }
      );
    },
  });

  jQuery("#addvideo").validate({
    submitHandler: function () {
      var typematerial = jQuery("#typematerial").val();
      var param = "&param=add_video&action=training_lib";
      var podata =
        jQuery("#addvideo").serialize() +
        "&typematerial=" +
        typematerial +
        param+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          window.location.reload();
          //window.location.href=redirectURL;
        } else {
        }
      });
    },
  });

  /*Add Video*/
  jQuery("#addMyVideo").on("click", function () {
    var typematerial = jQuery("#typematerial").val();
    var param = "&param=add_video&action=training_lib";
    var podata =
      jQuery("#addvideo").serialize() + "&typematerial=" + typematerial + param+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    jQuery.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = jQuery.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        window.location.reload();
        //window.location.href=redirectURL;
      } else {
        console.log("Failed");
      }
    });
  });

  jQuery("#addnote").validate({
    submitHandler: function () {
      var typematerial = jQuery("#typematerial").val();
      var param = "&param=add_note&action=training_lib";

      var notetxt = "";
      if (jQuery("#wp-descriptionnote-wrap").hasClass("tmce-active")) {
        notetxt = encodeURIComponent(
          tinyMCE.get("descriptionnote").getContent()
        );
      } else {
        notetxt = encodeURIComponent(jQuery("#descriptionnote").val());
      }
      if (notetxt == "") {
        alert("Please enter notes");
        return false;
      }
      var podata =
        jQuery("#addnote").serialize() +
        "&notetxt=" +
        notetxt +
        "&typematerial=" +
        typematerial +
        param+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          if (jQuery("#noteid").val() == 0) jQuery("#addnote").get(0).reset();
        }
        modadded++;
      });
    },
  });

  jQuery("#addMyNote").on("click", function () {
    var typematerial = jQuery("#typematerial").val();
    var param = "&param=add_note&action=training_lib";

    var notetxt = "";
    if (jQuery("#wp-descriptionnote-wrap").hasClass("tmce-active")) {
      notetxt = encodeURIComponent(tinyMCE.get("descriptionnote").getContent());
    } else {
      notetxt = encodeURIComponent(jQuery("#descriptionnote").val());
    }
    if (notetxt == "") {
      show_msg(1, "0 Notes Uploaded.");
      window.location.reload();
    }
    var podata =
      jQuery("#addnote").serialize() +
      "&notetxt=" +
      notetxt +
      "&typematerial=" +
      typematerial +
      param+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    jQuery.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = jQuery.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        if (jQuery("#noteid").val() == 0) jQuery("#addnote").get(0).reset();
      }
      modadded++;
    });
  });

  jQuery("#addhlink").validate({
    submitHandler: function () {
      var typematerial = jQuery("#typematerial").val();

      var param = "&param=add_hlink&action=training_lib";

      var podata =
        jQuery("#addhlink").serialize() +
        "&typematerial=" +
        typematerial +
        param+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      jQuery.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          if (jQuery("#helpnkid").val() == 0)
            jQuery("#addhlink").get(0).reset();
        }
        modadded++;
      });
    },
  });

  /*Add My Link*/
  jQuery("#addMyLink").on("click", function () {
    var typematerial = jQuery("#typematerial").val();

    var param = "&param=add_hlink&action=training_lib";

    var podata =
      jQuery("#addhlink").serialize() + "&typematerial=" + typematerial + param+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    jQuery.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = jQuery.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        if (jQuery("#helpnkid").val() == 0) jQuery("#addhlink").get(0).reset();
      }
      modadded++;
    });
  });

  jQuery(".meduploadimg").click(function (e) {
    e.preventDefault();
    var image = wp
      .media({
        title: "Upload Image",
        multiple: false,
        library: {
          type: "image", // This restricts the selection to images only
        },
      })
      .open()
      .on("select", function (e) {
        var uploaded_image = image.state().get("selection").first();
        var file_type = uploaded_image.get("type");

        // Check if the selected file is an image
        if (file_type === "image") {
          var image_url = uploaded_image.toJSON().url;
          jQuery(".uploadedimg").html('<img src="' + image_url + '">');
          jQuery(".mediaImgUrl").val(image_url);
        } else {
          alert("Please select only image files");
        }
      });
  });

  jQuery(document).on("click", "#mediauploadbtn", function (e) {
    e.preventDefault();
    var image = wp
      .media({
        title: "Upload Files",
        multiple: true,
      })
      .open()
      .on("select", function (e) {
        var uploaded_images = image.state().get("selection");
        var count = 0;
        var urls = [];
        var rurls = [];
        var anchors = [];
        var attachment_ids = uploaded_images
          .map(function (attachment) {
            attachment = attachment.toJSON();
            rurls.push(attachment.url);
            var splturl = attachment.url.split("/");
            var countp = splturl.length;
            urls.push(splturl[countp - 1]);
            anchors.push(
              "<a href='" +
                attachment.url +
                "' download class='mdfiles'>" +
                splturl[countp - 1] +
                "</a>"
            );
            count++;
          })
          .join();
        var html = "";
        for (var i = 0; i < anchors.length; i++) {
          html += anchors[i] + "<br/>";
        }
        jQuery("#mediainfo").addClass("rtr-alert rtr-alert-info");
        jQuery("#mediainfo").removeClass("alert-danger");
        jQuery("#mediainfo").html(
          "<span>" + anchors.length + " file(s) choose<br/>" + html + "</span>"
        );
      });
  });

  jQuery(document).on("click", ".document-upload", function () {
    if (jQuery(".mdfiles").length > 0) {
      var links = [];
      var names = [];
      var typematerial = jQuery("#typematerial").val();
      var id = 0;
      if (typematerial == "lesson") id = jQuery("#lessonid").val();
      else id = jQuery("#resourceid").val();

      jQuery(".mdfiles").each(function (i, obj) {
        links.push(obj.href);
        names.push(obj.text);
      });

      var podata =
        ajaxurl +
        "?&links=" +
        links +
        "&names=" +
        names +
        "&id=" +
        id +
        "&typematerial=" +
        typematerial +
        "&param=save_media_doc&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      $.post(ajaxurl, podata, function (response) {
        var data = $.parseJSON(response);
        show_msg(data.sts, data.msg);
        setTimeout(function () {
          location.reload();
        }, 1200);
      });
    } else {
      jQuery("#mediainfo").addClass("rtr-alert rtr-alert-danger");
      jQuery("#mediainfo").removeClass("alert-info");
      jQuery("#mediainfo").html("<span>Please Choose files to upload</span>");
    }
  });

  jQuery("#addimg").validate({
    submitHandler: function () {
      if (jQuery(".mediaImgUrl").val() == "") {
        show_msg(0, "Please choose a file");
        // alert("Please choose a file");
        return false;
      }

      var course_id = jQuery("#course_id").val();
      var urlimg = jQuery("#urlimg").val();
      var imgPath = jQuery(".mediaImgUrl").val();

      var podata =
        ajaxurl +
        "?&course_id=" +
        course_id +
        "&imgpath=" +
        imgPath +
        "&urlimg=" +
        urlimg +
        "&param=save_courseimg&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");

      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(dat);
        show_msg(data.sts, data.msg);
        setTimeout(function () {
          location.reload();
        }, 1200);
      });
    },
  });

  /* for user */
  jQuery("#userform").validate({
    submitHandler: function () {
      var is_enrol = 0;
      jQuery(".msgsml").html("Press Enter to check user available");
      var podata =
        jQuery("#userform").serialize() +
        "&is_enrol=" +
        is_enrol +
        "&course_id=" +
        jQuery("#reportcourse").val()+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=enroll_user&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".msgsml").html(data.msg);
        }
      );
    },
  });

  jQuery(document).on("keyup", "#uemail", function (e) {
    if ($.trim(jQuery(this).val()) == "") {
      jQuery(".msgsml").html("Press Enter to check user available");
    }
  });

  jQuery(document).on("click", ".btnenrolclk", function (e) {
    if (jQuery("#userform").valid()) {
      var is_enrol = 1;
      jQuery(".msgsml").html("Press Enter to check user available");
      var podata =
        jQuery("#userform").serialize() +
        "&is_enrol=" +
        is_enrol +
        "&course_id=" +
        jQuery("#reportcourse").val()+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=enroll_user&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".msgsml").html(data.msg);
          if (data.sts == 1) {
            window.location.reload();
          }
        }
      );
    }
  });

  /* for mentor */
  jQuery(document).on("keyup", "#memail", function (e) {
    if ($.trim(jQuery(this).val()) == "") {
      jQuery(".msgsml").html("Press Enter to check mentor available");
    }
  });

  jQuery("#mentorform").validate({
    submitHandler: function () {
      var is_enrol = 0;
      jQuery(".msgsml").html("Press Enter to mentor available");
      var podata =
        jQuery("#mentorform").serialize() +
        "&is_enrol=" +
        is_enrol +
        "&course_id=" +
        jQuery("#reportcourse").val()+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=add_mentor&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".msgsml").html(data.msg);
        }
      );
    },
  });

  jQuery(document).on("click", ".btnmentoradd", function (e) {
    if (jQuery("#mentorform").valid()) {
      var is_enrol = 1;
      jQuery(".msgsml").html("Press Enter to mentor available");
      var podata =
        jQuery("#mentorform").serialize() +
        "&is_enrol=" +
        is_enrol +
        "&course_id=" +
        jQuery("#reportcourse").val()+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=add_mentor&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".msgsml").html(data.msg);
          if (data.sts == 1) {
            window.location.reload();
          }
        }
      );
    }
  });

  jQuery(document).on("click", ".revoke_course_access", function (e) {
    var conf = confirm("Are you sure?");
    if (conf) {
      var enrol_id = jQuery(this).attr("data-id");
      jQuery(".msgsml").html("Press Enter to check user available");
      var podata = "enrol_id=" + enrol_id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=revoke_user&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".msgsml").html(data.msg);
          if (data.sts == 1) {
            window.location.reload();
          }
        }
      );
    }
  });

  jQuery(document).on("click", ".remove_mentor", function (e) {
    var conf = confirm("Are you sure?");
    if (conf) {
      var u_id = jQuery(this).attr("data-id");
      var course_id = jQuery("#reportcourse").val();
      jQuery(".msgsml").html("Press Enter to check mentor available");
      var podata = "course_id=" + course_id + "&u_id=" + u_id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=remove_mentor&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = jQuery.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".msgsml").html(data.msg);
          if (data.sts == 1) {
            window.location.reload();
          }
        }
      );
    }
  });
}

var timout;

function openvideodialog() {
  var txt = "";
  if (jQuery(".videotxt").length > 0) {
    jQuery(".btnupdt").text("Update");
    txt = jQuery(".videotxt").text();
  }
  jQuery("#embedcode").val(txt);
  open_modal("video_dialog");
}

function openvideodialog_call() {
  var last_id_inserted = jQuery("#last_id_inserted").val();
  if (last_id_inserted == 0) {
    jQuery("#call-title").focus();
    return false;
  }

  var txt = "";
  if (jQuery(".videotxt").length > 0) {
    jQuery(".btnupdt").text("Update");
    txt = jQuery(".videotxt").text();
  }
  jQuery("#embedcode").val(txt);
  open_modal("video_dialog");
}

function show_msg(sts, msg) {
  if (!jQuery(".msg").is(":visible")) {
    jQuery(".msg").show();
  }
  clearTimeout(timout);
  if (jQuery(".messdv").length == 0) {
    jQuery(".msg").html('<div class="messdv"></div>').show();
  }

  if (sts == 0) {
    jQuery(".messdv")
      .addClass("alert rtr-alert rtr-alert-danger")
      .html(
        ' <span class="glyphicon-exclamation-sign fa fa-exclamation-circle rtr-me-1" aria-hidden="true"></span> \n\
            <button type="button" class="rtr-notify-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n\
            ' + msg
      )
      .slideDown("slow");
  } else if (sts == 1) {
    jQuery(".messdv")
      .addClass(
        "alert rtr-alert-success rtr-d-flex rtr-align-items-center rtr-justify-content-center"
      )
      .html(
        ' <span class="rtr-glyphicon glyphicon-ok fa fa-check rtr-me-1" aria-hidden="true"> </span> \n\
            <button type="button" class="rtr-notify-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n\
            ' + msg
      )
      .slideDown("slow");
  }

  timout = setTimeout(function () {
    jQuery(".msg").slideUp("slow").html("").show();
  }, notification_timeout);
}

function show_msg_remove_exm(sts, msg) {
  clearTimeout(timout);
  if (jQuery(".messdv").length == 0) {
    jQuery(".msg").html('<div class="messdv"></div>').show();
  }

  if (sts == 0) {
    jQuery(".messdv")
      .addClass("rtr-alert rtr-alert-danger")
      .html(
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n\
            ' + msg
      )
      .slideDown("slow");
  } else if (sts == 1) {
    jQuery(".messdv")
      .addClass("rtr-alert rtr-alert-success")
      .html(
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n\
            ' + msg
      )
      .slideDown("slow");
  }

  timout = setTimeout(function () {
    jQuery(".msg").slideUp("slow").html("").show();
  }, notification_timeout);
}

function show_msgtime(sts, msg, time) {
  clearTimeout(timout);
  if (jQuery(".messdv").length == 0) {
    jQuery(".msg").html('<div class="messdv"></div>').show();
  }

  if (sts == 0) {
    jQuery(".messdv")
      .addClass("rtr-alert rtr-alert-danger")
      .html(
        ' <span class="rtr-glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> \n\
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n\
            ' + msg
      )
      .slideDown("slow");
  } else if (sts == 1) {
    jQuery(".messdv")
      .addClass("rtr-alert rtr-alert-success")
      .html(
        ' <span class="rtr-glyphicon glyphicon-ok" aria-hidden="true"></span> \n\
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n\
            ' + msg
      )
      .slideDown("slow");
  }

  timout = setTimeout(function () {
    jQuery(".msg").slideUp("slow").html("").show();
  }, time);
}

function open_modal(id) {
  jQuery("#" + id).show();
}
function close_modal() {
  jQuery(".modal").hide();
}

function submitmodle() {
  jQuery("#addmodules").submit();
}

function reset_form() {
  jQuery("form").each(function () {
    jQuery(this).get(0).reset();
  });

  if (jQuery(".btnupdt").length > 0) {
    jQuery(".btnupdt").text("Submit");
  }
  if (jQuery("#fileList").length > 0) {
    jQuery("#fileList").empty();
  }

  jQuery("#helpnkid").val("");
  jQuery("#noteid").val("");
  jQuery("#addmodules #id").val("");
  jQuery("#addlesson #lessid").val("");
  jQuery("#addlesson #lessid").val("");
  jQuery("#addresource #resid").val("");
}

function reset_form_call() {
  /*jQuery("form").each(function(){
     jQuery(this).get(0).reset();
     });*/

  if (jQuery(".btnupdt").length > 0) {
    jQuery(".btnupdt").text("Submit");
  }
  if (jQuery("#fileList").length > 0) {
    jQuery("#fileList").empty();
  }

  jQuery("#helpnkid").val("");
  jQuery("#noteid").val("");
  jQuery("#addmodules #id").val("");
  jQuery("#addlesson #lessid").val("");
  jQuery("#addlesson #lessid").val("");
  jQuery("#addresource #resid").val("");
}

function modalhide() {
  jQuery("#lesson_dialog,#confirm_dialog,.modealrealodonclose").on(
    "hidden.bs.modal",
    function () {
      if (modadded > 0) location.reload();
    }
  );
}

function genfuntions($) {
  jQuery(document).on("click", ".editmod", function () {
    var id = jQuery(this).attr("data-id");
    var dat = jQuery(".rowmod[data-id=" + id + "] td.text div.textdiv").html();
    var titl = jQuery(".rowmod[data-id=" + id + "] td.title").attr("data-txt");
    var lnk = jQuery(".rowmod[data-id=" + id + "] td.title").attr("data-lnk");
    jQuery("#id").val(id);
    if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
      tinyMCE.get("description").setContent(dat, { format: "html" });
    } else {
      jQuery("#description").val(dat);
    }

    jQuery("#title").val(titl);
    jQuery("#link").val(lnk);
    open_modal("confirm_dialog");
  });

  jQuery(document).on("click", ".deletemod", function () {
    var conf = confirm(
      "This will also delete all lessons associated with this module. Are you sure to delete?"
    );
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = jQuery("#addmodules").serialize() + "&id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_module&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmod[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".deletedoc", function () {
    var conf = confirm("Are you sure to delete?");
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_doc&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmoddoc[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".deletecou", function () {
    var conf = confirm(
      "This will also delete all module and lessons associated with this course. Are you sure to delete?"
    );
    if (conf) {
      var id = jQuery(this).attr("data-id");
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        "id=" + id + "&param=delete_course&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmod[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery("#frmPaypal").validate({
    submitHandler: function () {
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        jQuery("#frmPaypal").serialize() +
          "&param=addPaypalConfig&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");

          var data = $.parseJSON(dat);

          show_msg(data.sts, data.msg);
        }
      );
    },
  });

  jQuery(document).on("click", "#frmStripe", function () {
    jQuery("body").addClass("rtr-processing");
    var txtstripeSecretKey = jQuery("#txtstripeSecretKey").val();
    var txtstripePublisherKey = jQuery("#txtstripePublisherKey").val();
    $.post(
      ajaxurl,
      "txtstripeSecretKey=" +
        txtstripeSecretKey +
        "&txtstripePublisherKey=" +
        txtstripePublisherKey +
        "&param=addStripeConfig&action=training_lib",
      function (dat) {
        jQuery("body").removeClass("rtr-processing");

        var data = $.parseJSON(dat);

        show_msg(data.sts, data.msg);
      }
    );
  });

  jQuery("#course_type").on("change", function () {
    var type = jQuery(this).val();
    if (type == "paid") {
      jQuery("#course_amount").css("display", "block");
    } else {
      jQuery("#course_amount").css("display", "none");
    }
  });

  jQuery(document).on("click", ".editless", function () {
    var id = jQuery(this).attr("data-id");
    var dat = jQuery(".rowmod[data-id=" + id + "] td.text div.textdiv").html();
    var titl = jQuery(".rowmod[data-id=" + id + "] td.title").attr("data-txt");
    var lnk = jQuery(".rowmod[data-id=" + id + "] td.title").attr("data-lnk");
    jQuery("#lessid").val(id);

    if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
      tinyMCE.get("description").setContent(dat, { format: "html" });
    } else {
      jQuery("#description").val(dat);
    }

    jQuery("#title").val(titl);
    jQuery("#link").val(lnk);
    open_modal("lesson_dialog");
  });

  jQuery(document).on("click", ".deleteless", function () {
    var conf = confirm(
      "This will also delete associated resources. Are you sure to delete?"
    );
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = jQuery("#addlesson").serialize() + "&id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_lesson&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmod[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".deleteres", function () {
    var conf = confirm("Are you sure to delete?");
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = jQuery("#addresource").serialize() + "&id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_resource&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmod[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".deletelink", function () {
    var conf = confirm("Are you sure to delete?");
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_hlink&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmodlink[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".editres", function () {
    jQuery(".btnupdt").text("Update");
    var id = jQuery(this).attr("data-id");
    var dat = jQuery(".rowmod[data-id=" + id + "] td.text div.textdiv").html();
    var titl = jQuery(".rowmod[data-id=" + id + "] td.title").attr("data-txt");
    var lnk = jQuery(".rowmod[data-id=" + id + "] td.title").attr("data-lnk");
    var btntype = jQuery(".rowmod[data-id=" + id + "] td.title").attr(
      "data-btn"
    );
    var hrs = $.trim(jQuery(".rowmod[data-id=" + id + "] td.hrs").text());

    jQuery("#resid").val(id);

    if (jQuery("#wp-description-wrap").hasClass("tmce-active")) {
      tinyMCE.get("description").setContent(dat, { format: "html" });
    } else {
      jQuery("#description").val(dat);
    }

    jQuery("#button_type").val(btntype);
    jQuery("#title").val(titl);
    jQuery("#link").val(lnk);
    jQuery("#hours").val(hrs);
    open_modal("lesson_dialog");
  });

  jQuery(document).on("click", ".editnote", function () {
    var id = jQuery(this).attr("data-id");
    jQuery(".btnupdt").text("Update");
    var note = jQuery(
      ".rowmodnote[data-id=" + id + "] td.title .notetext"
    ).html();
    jQuery("#noteid").val(id);
    tinyMCE.get("descriptionnote").setContent(note, { format: "html" });
    //jQuery("#notetxt").val(note);
    open_modal("note_dialog");
  });

  jQuery(document).on("click", ".editlink", function () {
    var id = jQuery(this).attr("data-id");
    jQuery(".btnupdt").text("Update");
    var lnk = jQuery(".rowmodlink[data-id=" + id + "] td.title").attr(
      "data-link"
    );
    var lnktitle = jQuery(".rowmodlink[data-id=" + id + "] td.title").attr(
      "data-title"
    );
    jQuery("#helpnkid").val(id);
    jQuery("#linktitle").val(lnktitle);
    jQuery("#linkurl").val(lnk);
    open_modal("help_dialog");
  });

  jQuery(document).on("click", ".editComlink", function () {
    var id = jQuery(this).attr("data-id");
    var splittedParts = id.split("|");
    jQuery(".btnupdt").text("Update");
    jQuery("#helpnkid").val(id);
    jQuery("#linktitle").val(splittedParts[0]);
    jQuery("#linkurl").val(splittedParts[1]);
    open_modal("help_dialog");
  });

  jQuery(document).on("click", ".deletenote", function () {
    var conf = confirm("Are you sure?");
    if (conf) {
      jQuery("body").addClass("rtr-processing");
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id + "&param=delete_note&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          jQuery(".rowmodnote[data-id=" + id + "]").remove();
        }
      });
    }
  });

  jQuery(document).on("click", ".pemissioncourse", function () {
    if (jQuery(this).prop("checked") == true) {
      jQuery(".usersdivperm").slideDown("slow");
      jQuery("#userperm").removeClass("hidfield");
    } else {
      jQuery(".usersdivperm").slideUp("slow");
      jQuery("#userperm").addClass("hidfield");
    }
  });

  jQuery(document).on("click", ".moreinfo", function () {
    jQuery(this).closest(".smallinfo").hide();
    jQuery(this).closest(".smallinfo").next().show();
  });

  jQuery(document).on("click", ".lessinfo", function () {
    jQuery(this).closest(".largeinofinfo").hide();
    jQuery(this).closest(".largeinofinfo").prev().show();
  });

  jQuery(document).on("click", ".settingbtn", function () {
    var podata =
      jQuery("#settingform").serialize() +
      "&param=save_settings&action=training_lib";
    jQuery("body").addClass("rtr-processing")+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        window.location.reload();
      }
    });
  });

  jQuery(document).on("click", ".enroll", function () {
    var course = jQuery(this).attr("data-attr");

    var podata =
      "course=" + course + "&param=enroll_course&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");

    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(dat);
      show_msg(data.sts, data.msg);
      var url = jQuery("#url_redirect").val() + course;
      if (data.sts == 1) {
        setTimeout(function () {
          window.location.href = url;
        }, 3000);
      } else {
        window.location.href = url;
      }
    });
  });

  jQuery(document).on("click", ".markresource", function () {
    var typ = jQuery(this).attr("data-buttontype");
    var lestitle = jQuery(this).attr("data-lesson-title");
    var exercisetitle = jQuery(this).attr("data-exercise");
    var resource_id = jQuery(this).attr("data-attr");
    var page = jQuery(this).attr("data-page");
    jQuery("#page_type").val(page);
    if (typ == "mark") {
      var status = jQuery(this).attr("data-status");
      var uidadmincase = 0;
      if (jQuery("#uidused").length > 0) {
        uidadmincase = jQuery("#uidused").val();
      }
      var podata =
        "resource_id=" +
        resource_id +
        "&status=" +
        status +
        "&uidadmincase=" +
        uidadmincase +
        "&param=mark_resource&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");

      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        if (data.sts == 1) {
          if (status == "unmarked") {
            jQuery("#resource_" + resource_id)
              .removeClass("unmarkeddiv")
              .addClass("markeddiv");
            jQuery("#resource_" + resource_id + " .markresource")
              .attr("data-status", "marked")
              .text("Completed");
            calcpercent($, "inc");
            show_msg(data.sts, "Completed Successfully");
          } else {
            jQuery("#resource_" + resource_id)
              .removeClass("markeddiv")
              .addClass("unmarkeddiv");
            jQuery("#resource_" + resource_id + " .markresource")
              .attr("data-status", "unmarked")
              .text("Mark Complete");
            calcpercent($, "dec");
            show_msg(data.sts, "Unmarked Successfully");
          }
        } else {
          show_msg(data.sts, data.msg);
        }

        jQuery(".mypercentage").html(data.arr.percent + " % Complete");
        jQuery(".perdiv").attr(
          "style",
          "width:" + parseInt(data.arr.percent, 10) + "%"
        );
        setTimeout(function () {
          //location.reload();
        }, 1000);
      });
    } else {
      var obj = jQuery(this);
      jQuery("input[name=project_links]").val("");
      jQuery(".remove_project_ctn").hide();
      var uidadmincase = 0;
      if (jQuery("#uidused").length > 0) {
        uidadmincase = jQuery("#uidused").val();
      }
      var resou = jQuery(this).attr("data-attr");
      jQuery("body").addClass("rtr-processing");
      var podata =
        "typ=resource&resource_id=" +
        resou +
        "&uidadmincase=" +
        uidadmincase +
        "&param=get_links&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      $.post(ajaxurl, podata, function (dat) {
        var data = $.parseJSON(dat);
        jQuery("body").removeClass("rtr-processing");
        if (data.sts == 1) {
          var html = "";
          if (data.arr.links === "") {
          } else {
            html +=
              "<div id='removelinksdiv'><b>Submitted Links:</b><br/>" +
              data.arr.links +
              "<br/></div>";
          }
          if (data.arr.files === "") {
          } else {
            html +=
              "<div id='removefilesdiv'><b>Submitted Files:</b><br/>" +
              data.arr.files +
              "<br/></div>";
          }

          jQuery("#alreadyfoundfiles").html(html);
        } else if (data.sts == 0) {
          jQuery("#alreadyfoundfiles").html("");
        }

        var pos = obj.offset();
        var top = pos.top - 105;
        open_modal("subPrjModal");
        //jQuery(".mypercentage").html(data.arr.percent + " % Complete");
        jQuery("#resourse_id").val(resource_id);
        jQuery("#prjHier").html(lestitle + " > " + exercisetitle);
        jQuery(".project-submit-btn")
          .attr("data-typ", "resource")
          .attr("data-id", resou);
      });
    }
  });

  jQuery(document).on("click", ".removeProjectlinks", function () {
    var conf = confirm("Are you sure want to delete?");

    if (conf) {
      var type = jQuery(this).attr("data-type");
      var resource_id = jQuery(this).attr("data-id");
      var dt = jQuery("#single-course-div");
      var dtlesson = jQuery("#front-lesson-detail");
      var page = jQuery("#page_type").val();
      var dtexercise = jQuery("#single-exercise-div");

      var podata =
        "page=" +
        page +
        "&resource_id=" +
        resource_id +
        "&type=" +
        type +
        "&param=remove_project_resources&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      if (type == "link") {
        jQuery(this).closest("#removelinksdiv").remove();
      } else if (type == "file") {
        jQuery(this).closest("#removefilesdiv").remove();
      }
      $.post(ajaxurl, podata, function (response) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(response);
        toggleModal("#subPrjModal");
        if (page == "course") {
          setTimeout(function () {
            dt.html(data.arr.template);
          }, 200);
          jQuery(".perint").html(
            jQuery("#hiddenpercentage").val() + " % Complete"
          );
          jQuery(".resource_" + resource_id).attr("data-status", "unmarked");
        } else if (page == "lesson") {
          setTimeout(function () {
            dtlesson.html(data.arr.template);
          }, 200);
        } else if (page == "exercise") {
          setTimeout(function () {
            dtexercise.html(data.arr.template);
          }, 200);
        }

        show_msg(data.sts, data.msg);
      });
    }
  });

  jQuery(document).on("click", "#addmorelinks", function () {
    var table = jQuery("#tbllinks");
    table.append(
      '<tr><td><input style="margin-top: 2px;" type="url" class="form-control upd_links" name="upd_links[]" id="upd_links"/></td><td class="rtr-text-align-center"><button type="button" class="removelinks rtr-bg-transparent rtr-text-dark rtr-fs-20 rtr-p-0">&times;</button></td></tr>'
    );
  });

  jQuery(document).on("click", ".removelinks", function () {
    jQuery(this).closest("tr").remove();
  });

  jQuery(document).on("click", "#btnPrjUploadMedia", function (e) {
    e.preventDefault();
    var image = wp
      .media({
        title: "Upload Files",
        multiple: true,
      })
      .open()
      .on("select", function (e) {
        var uploaded_images = image.state().get("selection");
        var count = 0;
        var urls = [];
        var rurls = [];
        var attachment_ids = uploaded_images
          .map(function (attachment) {
            attachment = attachment.toJSON();
            rurls.push(attachment.url);
            var splturl = attachment.url.split("/");
            var countp = splturl.length;
            urls.push(splturl[countp - 1]);
            count++;
          })
          .join();

        jQuery("#mylinkschoosen").html(
          "Total " +
            count +
            " file(s) choosen<br/><b>Files</b>:<br/>" +
            urls.join("<br/>") +
            "<br/>"
        );
        jQuery("#mediafiles").val(rurls.join(","));
      });
  });

  jQuery(document).on("click", ".enrollbylist", function () {
    jQuery("#ernrolledlist").html(jQuery(".gifhidden").html());
    jQuery("#enrolled_dialog .modal-title").text(
      "Course - '" + jQuery(this).attr("data-title") + "' Enrolled Users"
    );
    open_modal("enrolled_dialog");
    var podata =
      "course_id=" +
      jQuery(this).attr("data-attr") +
      "&param=listenrolled&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);
      var users = data.arr;
      var ul = "<ul class='list-group'>";
      var li = "";
      var i = 0;
      for (a in users) {
        li +=
          "<li class='list-group-item'> " +
          users[a].display_name +
          " - " +
          users[a].user_email +
          " </li>";
        i++;
      }

      ul = li + "</ul>";
      if (i > 0) jQuery("#ernrolledlist").html(ul);
      else
        jQuery("#ernrolledlist").html(
          "Course is not enrolled by any user yet."
        );
    });
  });

  jQuery(document).on("click", ".view_mentor a", function () {
    jQuery("#mentorsid").html(jQuery(".gifhidden").html());
    open_modal("mentors_dialog");
    var podata =
      "ids=" +
      jQuery(this).attr("data-ids") +
      "&param=listmentorscourses&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);
      var users = data.arr;
      var ul = "<ul class='list-group'>";
      var li = "";
      var i = 0;
      for (a in users) {
        li +=
          "<li class='list-group-item'> " +
          users[a].display_name +
          " - " +
          users[a].user_email +
          " </li>";
        i++;
      }

      ul = li + "</ul>";
      if (i > 0) jQuery("#mentorsid").html(ul);
      else jQuery("#mentorsid").html("No Mentor Associated With This Course.");
    });
  });

  jQuery(document).on("click", ".cancelcall", function () {
    var conf = confirm("Are you sure?");
    if (conf) {
      jQuery("body").addClass("rtr-processing");
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id + "&param=cancel_call&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          jQuery(".rowmentor[data-id=" + id + "] td.status").html(
            "<div class='rtr-alert rtr-alert-danger'>cancelled</div>"
          );
          jQuery(".rowmentor[data-id=" + id + "] td.actiontd ").html(
            '<a href="javascript:;" data-id="' +
              id +
              '" class="deletecall rtr-btn rtr-btn-danger" title="Delete Call">Delete</a>'
          );

          if (jQuery(".detailcallpage").length > 0) {
            window.location.reload();
          }
        }
      });
    }
  });

  jQuery(document).on("click", ".deletecall", function () {
    var conf = confirm("Are you sure?");
    if (conf) {
      jQuery("body").addClass("rtr-processing");
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id + "&param=delete_call&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          jQuery(".rowmentor[data-id=" + id + "]").remove();
          if (jQuery(".detailcallpage").length > 0) {
            window.location.href = jQuery(".backbread").attr("href");
          }
        }
      });
    }
  });

  jQuery(document).on("click", ".btnclospop", function () {
    jQuery(".arrow_box.submit_project").hide();
  });

  jQuery(document).on("click", ".submitproj", function () {
    var obj = jQuery(this);
    jQuery("input[name=project_links]").val("");
    jQuery(".remove_project_ctn").hide();
    var proj = jQuery(this).attr("data-id");
    jQuery("body").addClass("rtr-processing");
    var podata = "proj=" + proj + "&param=get_links&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);
      jQuery("body").removeClass("rtr-processing");
      if (data.sts == 1) {
        jQuery("input[name=project_links]").val(data.arr.links);
        jQuery(".remove_project_ctn")
          .attr("data-typ", "exercise")
          .attr("data-id", proj)
          .show();
      }

      var pos = obj.offset();
      var top = pos.top - 105;
      jQuery(".arrow_box.submit_project")
        .attr("data-id", proj)
        .css({ display: "block", top: top });
      jQuery(".project-submit-btn")
        .attr("data-typ", "exercise")
        .attr("data-id", proj);
    });
  });

  jQuery(document).on("click", ".remove_project_ctn", function () {
    var proj = jQuery(this).attr("data-id");
    var datatyp = jQuery(this).attr("data-typ");
    jQuery("body").addClass("rtr-processing");
    var uidadmincase = 0;
    if (jQuery("#uidused").length > 0) {
      uidadmincase = jQuery("#uidused").val();
    }

    var podata =
      "proj=" +
      proj +
      "&datatyp=" +
      datatyp +
      "&uidadmincase=" +
      uidadmincase +
      "&param=remove_links&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);
      jQuery("body").removeClass("rtr-processing");
      if (data.sts == 1) {
        if (datatyp == "exercise") {
          jQuery("a.submitproj[data-id=" + proj + "]")
            .removeClass("linksumitted")
            .text("Submit Project");
          jQuery("#proj" + proj).removeClass("submittedproj");

          var ttx = "Submit project for this module";
          if (jQuery("#proj" + proj + " .projlnk").hasClass("lastfinal"))
            ttx = "Complete final project";

          jQuery("#proj" + proj + " .projlnk").html(
            '<a target="_blank" href="javascript:;">' + ttx + "</a>"
          );

          jQuery("#proj" + proj + " .sublinksstudents").hide();
          jQuery("#resource_" + proj + " .submittedfiles").hide();
          jQuery("#proj" + proj + " .projlinksdiv").empty();
        } else {
          jQuery(".markresource[data-attr=" + proj + "]")
            .attr("data-status", "unmarked")
            .text("Submit Project");
          jQuery("#resource_" + proj).removeClass("markeddiv");
          jQuery("#resource_" + proj + " .submittedfiles").hide();
          jQuery("#resource_" + proj + " .sublinksstudents").hide();
          jQuery("#resource_" + proj + " .projlinksdiv").empty();
        }

        calcpercent($, "dec");
        jQuery(".arrow_box.submit_project").hide();
        jQuery("input[name=project_links]").val("");
        jQuery(".remove_project_ctn").removeAttr("data-id").hide();
      }
    });
  });

  jQuery(document).on("click", ".submitProject", function () {
    var isUrlhasError = false;
    var mlinks = "";
    var st = 1;
    var totalUrlsCount = 0;
    jQuery(".upd_links").each(function () {
      var vl = $(this).val();
      if (vl != "") {
        if (!isValidUrl(vl)) {
          isUrlhasError = true;
          show_msg(0, "Please enter a valid Upload Link.");
        }
      }
      if (vl != "") {
        totalUrlsCount++;
        mlinks += $(this).val() + ",";
      }
    });

    if (isUrlhasError) {
      return;
    }

    jQuery("#upd_links").val("");

    if (jQuery("#mediafiles").val() !== "") {
      st = 2;
    } else if (totalUrlsCount > 0) {
      st = 3;
    }

    if (st == 1) {
      show_msg(0, "Please upload files/links");
      return false;
    }
    jQuery("body").addClass("rtr-processing");
    var submitButton = $(this);
    submitButton
      .text("Submitting, please wait...")
      .attr("disabled", true)
      .css({ cursor: "progress" });

    var podata =
      "page_type=" +
      jQuery("#page_type").val() +
      "&resourse_id=" +
      jQuery("#resourse_id").val() +
      "&mediafiles=" +
      jQuery("#mediafiles").val() +
      "&upd_links=" +
      mlinks +
      "&param=submit_project&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    var dt = jQuery("#single-course-div");
    var dtlesson = jQuery("#front-lesson-detail");
    var pageType = jQuery("#page_type").val();
    var dtexercise = jQuery("#single-exercise-div");

    $.post(ajaxurl, podata, function (response) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(response);
      if (data.sts == 1) {
        toggleModal("#subPrjModal");
        if (pageType == "lesson") {
          if (!isLesson_detailPage) {
            setTimeout(function () {
              dtlesson.html(data.arr.template);
            }, 200);

            jQuery("ul li.licls:nth-child(2)").removeClass("current");
            jQuery("ul li.licls:nth-child(2)").addClass("current");
            // not working need to check once more
            jQuery("div.description").css("display", "none");
            jQuery("div.description").attr("style", "");
            jQuery("div.resource").css("display", "block");
            jQuery("div.resource").attr("style", "");
          }
        } else if (pageType == "course") {
          setTimeout(function () {
            dt.html(data.arr.template);
          }, 200);
        } else if (pageType == "exercise") {
          setTimeout(function () {
            dtexercise.html(data.arr.template);
          }, 200);
        }

        jQuery(".perint").html(
          jQuery("#hiddenpercentage").val() + " % Complete"
        );
        show_msg(data.sts, data.msg);
        setTimeout(function () {
          //location.reload();
        }, 1000);
      }
      submitButton
        .text("Submit")
        .attr("disabled", false)
        .css({ cursor: "cursor" });
    });
  });

  jQuery(document).on("click", ".project-submit-btn", function () {
    /*custom code for exercise*/
    var dattyp = jQuery(this).attr("data-typ"); // param comes from exercise module tab
    var proj = jQuery(this).attr("data-id");
    var links = jQuery("input[name=project_links]").val();
    if (jQuery("#responsedoc").val() == "" && links == "") {
      show_msg(0, "Please choose file(s) or Please enter values");
      //alert("Please choose file(s) or Please enter values");
      return false;
    }
    jQuery("body").addClass("rtr-processing");
    var fstatus = 0;
    if (file_data.length > 0) {
      fstatus = 1;
    }
    var podata =
      "proj=" +
      proj +
      "&links=" +
      links +
      "&uidadmincase=" +
      uidadmincase +
      "&dattyp=" +
      dattyp +
      "&param=submit_links&action=training_lib&do=noupdate&fstatus=" +
      fstatus+"&nonce=" + rtr_script_data.nonce;

    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
    });

    return false;
  });

  jQuery(document).on("click", ".licls", function () {
    if (!jQuery(this).hasClass("current")) {
      jQuery(".licls").removeClass("current");
      jQuery(this).addClass("current");
      var clasother = jQuery(this).find("a").attr("data-type");
      jQuery(".clscomman").hide();
      jQuery("." + clasother).fadeIn("slow");
    }
  });

  jQuery(document).on("click", ".sumitted_projs", function () {
    open_modal("project_summitted");
    var id = jQuery(this).attr("data-id");
    var podata = "id=" + id + "&param=get_submissions&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);
      var tds = "";

      if (data.arr.projects && data.arr.projects != "") {
        var arlinks = data.arr.projects;

        for (x in arlinks) {
          tds += "<tr>";
          tds += "<td>" + arlinks[x].display_name + "</td>";
          tds += "<td>" + arlinks[x].user_email + "</td>";
          var lnks = arlinks[x].links;
          var doc_files = arlinks[x].doc_files;
          // lnks = lnks.split(",");
          var lnkanc = "";
          for (i = 0; i < lnks.length; i++) {
            lnkanc +=
              '<a target="_blank" href="' +
              lnks[i] +
              '" >' +
              lnks[i] +
              "</a> <br/>";
          }

          var doc_fileslnkanc = "";
          for (i = 0; i < doc_files.length; i++) {
            doc_fileslnkanc +=
              '<a target="_blank" href="' +
              doc_files[i] +
              '" >' +
              doc_files[i] +
              "</a> <br/>";
          }

          tds += "<td m_taable_link >" + lnkanc + "</td>";
          tds += "<td m_taable_link >" + doc_fileslnkanc + "</td>";

          tds += "</tr>";
        }
      } else {
        tds += "<tr>";
        tds += "<td colspan='3'>No record</td>";
        tds += "</tr>";
      }
      jQuery(".loadergif").hide();
      jQuery(".tbluserdv").show();
      jQuery(".tbluserdv tbody").html(tds);
    });
  });

  jQuery(document).on("click", ".reorder", function () {
    // jQuery("#reordermodal").modal();
    jQuery("#reordermodal").show();
    var id = jQuery(this).attr("data-id");
    var type = jQuery(this).attr("data-type");
    var podata =
      "id=" + id + "&type=" + type + "&param=get_rows&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);
      var tit = "Re-order Exercises [Drag & Drop Rows]";
      if (type == "modules") {
        tit = "Re-order Modules [Drag & Drop Rows]";
      } else if (type == "lessons") {
        tit = "Re-order Lessons [Drag & Drop Rows]";
      } else if (type == "courses") {
        tit = "Re-order Courses [Drag & Drop Rows]";
      }
      jQuery(".reordersave").attr("data-type", type);
      jQuery(".reordersave").attr("data-id", id);
      jQuery(".reordertitl").text(tit);
      if (data.sts == 1) {
        var rows = data.arr;
        var html = '<ul class="listul" id="sortableul">';
        for (a in rows) {
          html +=
            '<li class="listli" data-id="' +
            rows[a].id +
            '" data-ord="' +
            rows[a].ord +
            '">' +
            rows[a].title +
            "</li>";
        }
        html += "</ul>";
        jQuery("#reorderrows").html(html);
        sortul();
      } else {
        jQuery("#reorderrows").html(data.msg);
      }
    });
  });

  jQuery(document).on("click", ".movelesson", function () {
    jQuery("#movemodal").show();
    var id = jQuery(this).attr("data-id");
    var type = jQuery(this).attr("data-type");
    var podata =
      "id=" + id + "&type=" + type + "&param=get_moverows&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      var data = $.parseJSON(dat);

      var tit = "Move Exercises";
      if (type == "modules") {
        tit = "Move Modules";
      } else if (type == "lessons") {
        tit = "Move Lessons";
      } else if (type == "courses") {
        tit = "Move Courses";
      }
      jQuery(".reordersave").attr("data-type", type);
      jQuery(".reordersave").attr("data-id", id);
      jQuery(".reordertitl").text(tit);
      if (data.sts == 1) {
        var mods = data.arr.rows_modules;
        var rows = data.arr.rows;

        var select =
          '<div class="control-group"><label class="rtr-col-lg-2">Select Module</label><select style="margin-top: 10px; width:100%;" class="rtr-form-control" name="module" id="module">';
        var module_id = jQuery("#module_id").val();
        for (x in mods) {
          var chk = "";
          if (module_id == mods[x].id) {
            chk = 'selected="selected"';
          }
          select +=
            "<option " +
            chk +
            ' value="' +
            mods[x].id +
            '" >' +
            mods[x].title +
            "</option>";
        }

        select += "</select></div>";

        var html = '<div class="control-group"><ul class="listul">';
        for (a in rows) {
          html +=
            '<li class="movelistli" data-id="' +
            rows[a].id +
            '" data-ord="' +
            rows[a].ord +
            '">\n\
                    <label> <input class="chmove" type="checkbox" name="chkrows" value="' +
            rows[a].id +
            '" />' +
            rows[a].title +
            "</label></li>";
        }
        html +=
          '</ul></div>\n\
                <div class="row"></div>' + select;

        jQuery("#moverows").html(html);
        sortul();
      } else {
        jQuery("#moverows").html(data.msg);
      }
    });
  });

  jQuery(document).on("click", ".reordersave", function () {
    var id = jQuery(this).attr("data-id");
    var type = jQuery(this).attr("data-type");

    var armult = [];
    var i = 1;
    jQuery("#sortableul li").each(function () {
      armult.push(jQuery(this).attr("data-id"));
      i++;
    });
    if (i == 1) {
      //alert("No Row Found");
      show_msg(0, "No Row Found");
      return false;
    }
    var podata =
      "armult=" +
      armult +
      "&id=" +
      id +
      "&type=" +
      type +
      "&param=save_rows&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        setTimeout(function () {
          window.location.reload();
        }, 1200);
      }
    });
  });

  jQuery(document).on("click", ".movesave", function () {
    var id = jQuery(this).attr("data-id");
    var type = jQuery(this).attr("data-type");

    var modid = jQuery("#module").val();
    if (modid == "") {
      show_msg(0, "Please select a module");
      //alert("please select a module");
      return false;
    }
    var armult = [];
    jQuery(".chmove").each(function () {
      if (jQuery(this).prop("checked") == true) {
        armult.push(jQuery(this).val());
      }
    });
    if (armult.length <= 0) {
      show_msg(0, "No Lesson Selecetd");
      //alert("No Lesson Selecetd");
      return false;
    }

    var podata =
      "armult=" +
      armult +
      "&modid=" +
      modid +
      "&param=move_rows&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        window.location.reload();
      }
    });
  });

  jQuery(document).on("click", ".uploadcourseimg", function () {
    var id = jQuery(this).attr("data-id");
    jQuery("#course_id").val(id);
    var row = jQuery(".rowmod[data-id=" + id + "] td.imgtd ");
    var img = row.attr("data-img");
    if (img != "") {
      jQuery(".uploadedimg").html("<img src='" + img + "' />");
    }
    var link = row.attr("data-link");
    if (link != "") {
      jQuery("#urlimg").val(link);
    }
    jQuery("#image_dialog").show();
  });

  jQuery(document).on("click", ".generatereport", function () {
    var id = jQuery("#reportcourse").val();
    if (id != "") {
      if (jQuery(".mentorhandlepage").length > 0) {
        window.location.href =
          "admin.php?page=course_admin_mentors&course=" + id;
      } else {
        window.location.href = "admin.php?page=course_admin&course=" + id;
      }
    } else alert("Please select a course");
  });

  jQuery(document).on("click", ".reschedulecall", function () {
    var id = jQuery(this).attr("data-id");
    window.location.href = "admin.php?page=manage_mentor_calls&call_id=" + id;
    return false;

    var course_id = jQuery("tr.rowmentor[data-id=" + id + "] td.title").attr(
      "data-title"
    );
    jQuery("#courseid").val(course_id);
    var user_id = jQuery("tr.rowmentor[data-id=" + id + "] td.name").attr(
      "data-name"
    );
    jQuery("#student_user").val(user_id);
    var link = jQuery("tr.rowmentor[data-id=" + id + "] td.name").attr(
      "data-link"
    );
    jQuery("#meetinglink").val(link);
    var date = jQuery("tr.rowmentor[data-id=" + id + "] td.date").attr(
      "data-date"
    );
    jQuery("#datecall").val(date);
    var isrecur = jQuery("tr.rowmentor[data-id=" + id + "] td.isrecur").attr(
      "data-isrecur"
    );

    if (isrecur == 1) jQuery("#recurcall").prop("checked", true);
    else jQuery("#recurcall").prop("checked", false);

    if (jQuery("#mentorselect").length > 0) {
      var mentor_id = jQuery("tr.rowmentor[data-id=" + id + "] td.mentor").attr(
        "data-id"
      );
      if (typeof mentor_id === "undefined") {
        jQuery("#student_user").val(user_id);
      } else {
        jQuery("#mentorselect").val(mentor_id);
        var course_id = $.trim(jQuery("#courseid").val());
        mentro_students(course_id, mentor_id, user_id);
      }
    }

    jQuery("#callid").val(id);
    jQuery(".invtbtn").text("Re-Schedule Call");

    jQuery("html, body").animate({ scrollTop: 0 }, 500, function () {
      jQuery("#datecall").focus();
    });
  });

  jQuery(document).on("click", ".attendeornot", function () {
    var name = jQuery(this).attr("data-name");
    var val = jQuery(this).val();
    var txt = name + " has not attended";
    if (val == "yes") {
      txt = name + " has attended";
    }
    var conf = confirm("Are you sure, " + txt + " this call?");
    if (conf) {
      jQuery("body").addClass("rtr-processing");
      var id = jQuery(this).attr("data-id");
      var podata =
        "id=" +
        id +
        "&val=" +
        val +
        "&param=markattendence&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          var ststxt =
            '<div class="divattended"><div>Call Attended</div><div class="rtr-alert rtr-alert-danger">No</div></div>';
          if (val == "yes") {
            ststxt =
              '<div class="divattended"><div>Call Attended</div><div class="rtr-alert rtr-alert-success">Yes</div></div>';
          }
          jQuery("tr.rowmentor[data-id=" + id + "] .attdiv").html(ststxt);
        }
      });
    }
  });

  jQuery(document).on("click", ".assignmentor", function (e) {
    jQuery(".dddiv").empty().hide();
    jQuery(".btndiv").show();
    var uid = jQuery(this).attr("data-uid");
    var mentor = jQuery(".mentorrow[data-uid=" + uid + "] td.mentortd").attr(
      "data-mid"
    );
    if (jQuery(".coursereportpage").length > 0) {
      mentor = jQuery(".mentorrow[data-uid=" + uid + "] .mentorspan").attr(
        "data-mid"
      );
    }
    var listdd = jQuery(".mentordd").html();
    jQuery(".mentorrow[data-uid=" + uid + "] .btndiv").hide();
    jQuery(".mentorrow[data-uid=" + uid + "] .dddiv")
      .html(listdd)
      .show();
    jQuery(".dddiv #mentordropdown").val(mentor);
    e.stopPropagation();
    return false;
  });

  jQuery(document).on("click", "#select_all", function (e) {
    if (jQuery(this).prop("checked"))
      jQuery(".chkcommon").prop("checked", true);
    else jQuery(".chkcommon").prop("checked", false);
  });

  jQuery(document).on("click", ".assignselected", function (e) {
    var ar = [];
    jQuery(".chkcommon").each(function () {
      if (jQuery(this).prop("checked")) {
        ar.push(jQuery(this).val());
      }
    });
    if (ar.length == 0) {
      alert("Please select at least one user");
      return false;
    }
    var mentor = jQuery(".assigntosel #mentordropdown").val();
    if (mentor == "") {
      alert("Please select mentor");
      return false;
    }

    jQuery("body").addClass("rtr-processing");
    var course_id = jQuery("#courseselect").val();
    var podata =
      "ar=" +
      ar +
      "&course_id=" +
      course_id +
      "&mentor=" +
      mentor +
      "&param=assignmentormultiple&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        window.location.reload();
      }
    });
  });

  jQuery(document).on("change", "#courseselect", function (e) {
    var course = jQuery(this).val();
    if (course != "")
      window.location.href = "admin.php?page=map_mentors&course=" + course;
  });

  jQuery(document).on("click", ".resetcall", function (e) {
    window.location.href = "admin.php?page=manage_mentor_calls";
  });

  jQuery(document).on("change", "#courseid", function (e) {
    var course = jQuery(this).val();
    if (course != "")
      window.location.href =
        "admin.php?page=manage_mentor_calls&course=" + course;
  });

  jQuery(document).on("click", ".dddiv #mentordropdown", function (e) {
    e.stopPropagation();
    return false;
  });
  jQuery(document).on("change", ".dddiv #mentordropdown", function (e) {
    var course_id = 0;
    if (jQuery(".coursereportpage").length > 0) {
      course_id = jQuery("#reportcourse").val();
    } else {
      course_id = jQuery("#courseselect").val();
    }

    var uid = jQuery(this).parent().attr("data-uid");
    var id = jQuery(this).parent().attr("data-id");
    var mentor = $.trim(jQuery(this).val());
    if (mentor == "") {
      alert("Please select a mentor");
      return false;
    }
    jQuery("body").addClass("rtr-processing");

    var isdel = 0;
    if (mentor == "") isdel = 1;

    var podata =
      "id=" +
      id +
      "&uid=" +
      uid +
      "&course_id=" +
      course_id +
      "&isdel=" +
      isdel +
      "&mentor=" +
      mentor +
      "&param=assignmentor&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
    $.post(ajaxurl, podata, function (dat) {
      jQuery("body").removeClass("rtr-processing");
      var data = $.parseJSON(dat);
      show_msg(data.sts, data.msg);
      if (data.sts == 1) {
        var selectedtxt = jQuery(
          ".dddiv #mentordropdown option:selected"
        ).text();
        if (mentor == "") selectedtxt = "Not Assigned";

        if (jQuery(".coursereportpage").length > 0) {
          jQuery(
            ".coursereportpage .mentorrow[data-uid=" + uid + "] span.mentorspan"
          ).html(selectedtxt);
          jQuery(
            ".coursereportpage .mentorrow[data-uid=" + uid + "] .btndiv a"
          ).text("Change Mentor");
        } else {
          jQuery(".mentorrow[data-uid=" + uid + "] td.mentortd").html(
            selectedtxt
          );
        }
        if (data.arr > 0) {
          jQuery(".mentorrow[data-uid=" + uid + "] td.mentortd").attr(
            "data-mid",
            data.arr
          );
          if (jQuery(".coursereportpage").length > 0) {
            jQuery(".mentorrow[data-uid=" + uid + "] .mentorspan").attr(
              "data-mid",
              data.arr
            );
          }
        } else {
          jQuery(".mentorrow[data-uid=" + uid + "] td.mentortd").attr(
            "data-mid",
            ""
          );
          if (jQuery(".coursereportpage").length > 0) {
            jQuery(".mentorrow[data-uid=" + uid + "] .mentorspan").attr(
              "data-mid",
              ""
            );
          }
        }

        jQuery(".dddiv").empty().hide();
        jQuery(".btndiv").show();
      }
    });

    e.stopPropagation();
    return false;
  });

  jQuery(document).on("click", ".callsch", function (e) {
    var course = jQuery(this).attr("data-course");
    var user = jQuery(this).attr("data-uid");
    var mentor_id = 0;
    if (
      typeof jQuery("tr.mentorrow[data-uid=" + user + "] span.mentorspan").attr(
        "data-mid"
      ) !== "undefined"
    ) {
      mentor_id = jQuery(
        "tr.mentorrow[data-uid=" + user + "] span.mentorspan"
      ).attr("data-mid");
    }
    window.location.href =
      "admin.php?page=manage_mentor_calls&mentor=" +
      mentor_id +
      "&user=" +
      user +
      "&course=" +
      course;
  });

  jQuery(document).on("change", "#mentorselect", function (e) {
    var course_id = $.trim(jQuery("#courseid").val());
    var mentor = $.trim(jQuery(this).val());
    mentro_students(course_id, mentor, 0);
  });

  jQuery(document).on("click", function () {
    jQuery(".dddiv").empty().hide();
    jQuery(".btndiv").show();
  });

  jQuery(document).on("click", ".openform", function () {
    //var id = jQuery("#creatementorform").val();
    var isupdt = jQuery(this).attr("data-update");
    var id = 1;
    if (id > 0) loadform(id, isupdt);
    else alert("Please select mentor");

    /* var id='';
         loadform(id,isupdt);*/
  });

  jQuery(document).on("click", ".deletesurveyform", function () {
    var conf = confirm(
      "This will also delete all survey results for this form. Are you sure to delete?"
    );
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_surveyform&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmod[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".deletesurvey", function () {
    var conf = confirm("Are you sure to delete?");
    if (conf) {
      var id = jQuery(this).attr("data-id");
      var podata = "id=" + id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=delete_survey&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          jQuery(".rowmod[data-id=" + id + "]").remove();
        }
      );
    }
  });

  jQuery(document).on("click", ".sendsurvey", function () {
    var conf = confirm("Are you sure to send survey?");
    if (conf) {
      var formid = jQuery("#formid").val();
      /*Custom code to select all checked users*/
      var ids = [];

      jQuery("input.chkSt:checkbox:checked").each(function () {
        ids.push(jQuery(this).val());
      });
      if (ids.length == "") {
        alert("No user found");
        return false;
      }

      var course_id = jQuery(".showCourseList").val();

      var podata =
        "formid=" + formid + "&users=" + ids + "&course_id=" + course_id+"&nonce=" + rtr_script_data.nonce;
      jQuery("body").addClass("rtr-processing");
      $.post(
        ajaxurl,
        podata + "&param=survey_send&action=training_lib",
        function (dat) {
          jQuery("body").removeClass("rtr-processing");
          var data = $.parseJSON(dat);
          show_msg(data.sts, data.msg);
          if (data.sts == 1) {
            setInterval(function () {
              window.location.reload();
            }, 1200);
          }
        }
      );
    }
  });

  jQuery(document).on("click", ".template_update", function () {
    var template_id = jQuery(this).attr("data-id");
    var sub = $.trim(jQuery("#subject_" + template_id).val());
    var content = "";
    if (
      jQuery("#wp-content_" + template_id + "-wrap").hasClass("tmce-active")
    ) {
      content = encodeURIComponent(
        tinyMCE.get("content_" + template_id).getContent()
      );
    } else {
      content = encodeURIComponent(jQuery("#content_" + template_id).val());
    }
    content = $.trim(content);
    if (sub == "" || content == "") {
      alert("Please fill Subject and Content field.");
      return false;
    }

    var podata =
      "template_id=" + template_id + "&sub=" + sub + "&content=" + content+"&nonce=" + rtr_script_data.nonce;
    jQuery("body").addClass("rtr-processing");
    $.post(
      ajaxurl,
      podata + "&param=update__template&action=training_lib",
      function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        show_msg(data.sts, data.msg);
      }
    );
  });
}

function mentro_students(course_id, mentor, user_id) {
  jQuery("body").addClass("rtr-processing");
  var podata =
    "mentor=" +
    mentor +
    "&course_id=" +
    course_id +
    "&param=get_mentor_users&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
  $.post(ajaxurl, podata, function (dat) {
    jQuery("body").removeClass("rtr-processing");
    var data = $.parseJSON(dat);
    var options = "";
    if (data.sts == 1) {
      var dd = data.arr;
      for (a in dd) {
        var sel = "";
        if (user_id == dd[a].ID) sel = 'selected="selected"';
        options +=
          "<option " +
          sel +
          " value='" +
          dd[a].ID +
          "'>" +
          dd[a].display_name +
          "</option>";
      }
    }
    jQuery("#student_user").html(options);
  });
}

function sortul() {
  jQuery("#sortableul").sortable();
  jQuery("#sortableul").disableSelection();
}

function calcpercent($, type) {
  var comres = jQuery("#completed_resources").val();
  var totres = jQuery("#total_resources").val();

  if (type == "inc") {
    comres = parseInt(comres) + 1;
  } else {
    comres = parseInt(comres) - 1;
  }
  if (comres < 0) {
    comres = 0;
  } else if (comres >= totres) {
    comres = totres;
  }

  var percent = Math.floor((comres / totres) * 100);

  jQuery("#completed_resources").val(comres);
  jQuery("#percent_bar").val(percent);
  jQuery(".perint").text(percent);
  jQuery(".perdiv").css("width", percent + "%");
}

// submit functions

function submitlesson() {
  jQuery("#addlesson").submit();
}

function submitres() {
  jQuery("#addresource").submit();
}

function getexceise(type, id) {
  $ = jQuery;
  jQuery("#addprojectexce").get(0).reset();
  var podata =
    "type=" + type + "&id=" + id + "&param=get_exercise&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
  jQuery("body").addClass("rtr-processing");
  $.post(ajaxurl, podata, function (dat) {
    jQuery("body").removeClass("rtr-processing");
    var data = $.parseJSON(dat);

    if (data.arr.info) {
      var vals = data.arr.info;
      if (vals.status == 1) {
        jQuery("#addprojectexce #isenabled").prop("checked", true);
      }
      jQuery("#exid").val(vals.id);
      jQuery("#addprojectexce #title").val(vals.title);
      jQuery("#addprojectexce #hours").val(vals.total_hrs);

      tinyMCE.get("description1").setContent(vals.desc, { format: "html" });
      //tinyMCE.get('description1').setContent(vals.desc, {format : 'html'} );
    }
    jQuery(".tbluserdv tbody").html("");
    jQuery("#listusersdiv .loadergif").remove();
    jQuery(".tbluserdv").show();
    var tds = "";

    if (data.arr.projects && data.arr.projects != "") {
      var arlinks = data.arr.projects;

      for (x in arlinks) {
        tds += "<tr>";
        tds += "<td>" + arlinks[x].display_name + "</td>";
        tds += "<td>" + arlinks[x].user_email + "</td>";
        var lnks = arlinks[x].links;
        lnks = lnks.split(",");
        var lnkanc = "";
        for (i = 0; i < lnks.length; i++) {
          lnkanc +=
            '<a target="_blank" href="' +
            lnks[i] +
            '" >' +
            lnks[i] +
            "</a> <br/>";
        }

        tds += "<td>" + lnkanc + "</td>";
        tds += "</tr>";
      }
    } else {
      tds += "<tr>";
      tds += "<td colspan='3'>No record</td>";
      tds += "</tr>";
    }

    jQuery(".tbluserdv tbody").html(tds);
    open_modal("project_excercise");
  });
}

function funs($) {
  jQuery("#responsedoc").change(function () {
    readdoc(this);
  });

  function readdoc(input) {
    if (input.files.length > 0) jQuery("#fileList").html("");

    for (var x = 0; x < input.files.length; x++) {
      //add to list
      var li = document.createElement("li");
      var inp =
        "<input class='form-control' type='text' name='doctitles[]' placeholder='Enter Document Title' />";
      li.innerHTML =
        "<p>File " +
        (x + 1) +
        ":  " +
        input.files[x].name +
        "</p>\n\
                            <div>" +
        inp +
        "</div>";
      jQuery(li).addClass("rtr-list-group-item");
      jQuery("#fileList").append(li);
    }
  }
}

function uploaddocs() {
  if (jQuery("#responsedoc").val() == "") {
    alert("Please choose file(s)");
    return false;
  }
  var typematerial = jQuery("#typematerial").val();

  var id = 0;
  if (typematerial == "lesson") id = jQuery("#lessonid").val();
  else id = jQuery("#resourceid").val();

  var data = new FormData();
  $.each(jQuery("#responsedoc")[0].files, function (i, file) {
    data.append("file-" + i, file);
  });

  var podata =
    ajaxurl +
    "?id=" +
    id +
    "&typematerial=" +
    typematerial +
    "&param=save_doc&action=training_lib"+"&nonce=" + rtr_script_data.nonce;

  jQuery("body").addClass("rtr-processing");

  $.ajax({
    type: "POST",
    url: podata,
    data: data,
    processData: false,
    contentType: false,
    success: function (msg) {
      var data = $.parseJSON(msg);
      show_msg(data.sts, data.msg);
      setdoctitles(data.arr.ids, data.arr.pos);

      window.location.reload();
    },
    error: function (msg) {
      jQuery("#fileList").empty();
      jQuery("#responsedoc").val("");
      jQuery("body").removeClass("rtr-processing");
    },
  });
}

/*Add My Docs*/
function uploadMydocs() {
  if (jQuery("#responsedoc").val() == "") {
    /*alert("Please choose file(s)");
         return false;*/
    show_msg(1, "0 Files Uploaded.");
    window.location.reload();
  }
  var typematerial = jQuery("#typematerial").val();

  var id = 0;
  if (typematerial == "lesson") id = jQuery("#lessonid").val();
  else id = jQuery("#resourceid").val();

  var data = new FormData();
  $.each(jQuery("#responsedoc")[0].files, function (i, file) {
    data.append("file-" + i, file);
  });

  var podata =
    ajaxurl +
    "?id=" +
    id +
    "&typematerial=" +
    typematerial +
    "&param=save_doc&action=training_lib"+"&nonce=" + rtr_script_data.nonce;

  jQuery("body").addClass("rtr-processing");

  $.ajax({
    type: "POST",
    url: podata,
    data: data,
    processData: false,
    contentType: false,
    success: function (msg) {
      var data = $.parseJSON(msg);
      show_msg(data.sts, data.msg);
      setdoctitles(data.arr.ids, data.arr.pos);

      window.location.reload();
    },
    error: function (msg) {
      jQuery("#fileList").empty();
      jQuery("#responsedoc").val("");
      jQuery("body").removeClass("rtr-processing");
    },
  });
}

function setdoctitles(ids, pos) {
  var podata =
    jQuery("#adddoc").serialize() +
    "&ids=" +
    ids +
    "&pos=" +
    pos +
    "&param=save_doc_titles&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
  $.post(ajaxurl, podata, function (dat) {
    var data = $.parseJSON(dat);
    jQuery("body").removeClass("rtr-processing");
    show_msg(data.sts, data.msg);
    jQuery("#fileList").empty();
    jQuery("#responsedoc").val("");
    if (data.sts == 1) {
      window.location.reload();
    }
  });
}

function uploadimg(id, urlimg, typematerial) {
  if (jQuery("#responseimg").val() != "") {
    var data = new FormData();
    $.each(jQuery("#responseimg")[0].files, function (i, file) {
      data.append("file-" + i, file);
    });

    var podata =
      ajaxurl +
      "?id=" +
      id +
      "&typematerial=" +
      typematerial +
      "&urlimg=" +
      urlimg +
      "&param=save_img&action=training_lib"+"&nonce=" + rtr_script_data.nonce;

    jQuery("body").addClass("rtr-processing");

    $.ajax({
      type: "POST",
      url: podata,
      data: data,
      processData: false,
      contentType: false,
      success: function (msg) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(msg);
        show_msg(data.sts, data.msg);
        if (data.sts == 1) {
          window.location.reload();
        }
      },
      error: function (msg) {
        jQuery("body").removeClass("rtr-processing");
      },
    });
  }
}

function saveimgurl(urlimg) {
  var imageid = jQuery("#imageid").val();
  var podata =
    ajaxurl +
    "?imageid=" +
    imageid +
    "&urlimg=" +
    urlimg +
    "&param=save_urlimg&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
  jQuery("body").addClass("rtr-processing");
  $.post(podata, function (dat) {
    jQuery("body").removeClass("rtr-processing");
    var data = $.parseJSON(dat);
    show_msg(data.sts, data.msg);
    if (data.sts == 1) {
      window.location.reload();
    }
  });
}

function ValidURL(s) {
  var regexp =
    /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
  return regexp.test(s);
}

function loadform(id, isupdt) {
  if (jQuery(".customformbuilder").length > 0) {
    if (isupdt == 1) {
      jQuery("body").addClass("rtr-processing");
      var form_id = jQuery("#formid").val();
      formupdate(dataofform, form_id);
    } else {
      var mentor_id = jQuery("#creatementorform").val();
      var formtitle = jQuery("#formtitle").val();
      if (formtitle == "") {
        alert("Please enter form title");
        return false;
      }

      var podata =
        "mentor_id=0&formtitle=" +
        formtitle +
        "&param=saveform&action=training_lib"+"&nonce=" + rtr_script_data.nonce;

      jQuery("body").addClass("rtr-processing");
      $.post(ajaxurl, podata, function (dat) {
        jQuery("body").removeClass("rtr-processing");
        var data = $.parseJSON(dat);
        if (data.sts == 1) {
          window.location.href =
            "admin.php?page=new_survey&form_id=" + data.arr;
        }
      });
    }
  } else {
  }
}

function formupdate(payload, form_id) {
  var formtitle = jQuery("#formtitle").val();
  var podata =
    "form_id=" +
    form_id +
    "&formtitle=" +
    formtitle +
    "&form_data=" +
    payload +
    "&param=updateform&action=training_lib"+"&nonce=" + rtr_script_data.nonce;

  $.post(ajaxurl, podata, function (dat) {
    jQuery("body").removeClass("rtr-processing");
    var data = $.parseJSON(dat);
    if (data.sts == 1) {
    }
  });
}

function formbuilder(form_id, formjson) {
  if (jQuery(".customformbuilder").length > 0) {
    var fbbuild = new Formbuilder({
      selector: ".customformbuilder",
      bootstrapData: formjson,
    });

    fbbuild.on("save", function (payload) {
      dataofform = payload;
      formupdate(dataofform, form_id);
    });
  }
}

function savesurveydata(survey_id, values) {
  values = JSON.stringify(values);
  var podata =
    "survey_id=" +
    survey_id +
    "&values=" +
    values +
    "&param=saveformresult&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
  jQuery("body").addClass("rtr-processing");
  $.post(ajaxurl, podata, function (dat) {
    jQuery("body").removeClass("rtr-processing");
    var data = $.parseJSON(dat);
    show_msg(data.sts, data.msg);
    if (data.sts == 1) {
      setTimeout(function () {
        //window.location.reload();
      }, 3000);
    }
  });
}

function chosen_initilize() {
  if (jQuery(".chosen").length > 0) {
    jQuery(".chosen").chosen({
      width: "100%",
      no_results_text: "Oops, nothing found!",
    });
  }
}

/*Custom Js Code For Survey Area - custom*/
jQuery(function () {
  var lastid = jQuery("#last_id_inserted").val();
  var editid = jQuery("#editid").val();

  if (lastid == 0 && editid == 0) {
    jQuery(".call-div-dis").attr("disabled", "true");
    //jQuery(".call-div-dis").attr("onclick","");
  } else {
  }

  if (editid > 0) {
    jQuery(".call-div-dis").removeAttr("disabled");
  }

  jQuery("#rdbCourse,#rdbMentor,.show_students").hide();
  jQuery(".rdbSurvey").on("click", function () {
    targetValue = jQuery(this).attr("data-target");
    if (targetValue == "rdbCourse") {
      jQuery("#rdbCourse").show();
      jQuery("#rdbMentor").hide();
      jQuery(".show_students").hide();
    }
    if (targetValue == "rdbMentor") {
      jQuery("#rdbCourse").hide();
      jQuery("#rdbMentor").show();
      jQuery(".show_students").hide();
    }
  });

  jQuery(".showCourseList").on("change", function () {
    var course_id = jQuery(this).val();
    var coursedata =
      ajaxurl + "?param=get_survey_users_by_course&action=training_lib";
    jQuery("body").addClass("rtr-processing");
    $.ajax({
      url: coursedata,
      data: { cour_id: course_id },
      type: "post",
      success: function (resp) {
        jQuery("body").removeClass("rtr-processing");
        var data = JSON.parse(resp);
        if (data.arr.length > 0) {
          var list = data.arr;
          var listhtml = "<ol class='load-st-ol'>";
          $.each(list, function (i, item) {
            listhtml +=
              "<li><input type='checkbox' class='chkSt' name='chkusers' value='" +
              item.id +
              "'/>" +
              item.user_email +
              "</li>";
          });
          listhtml += "</ol>";
          jQuery(".show_students").css("display", "block");
          jQuery(".head").html(
            "<label class='slct_heading'>Select Users</label>"
          );
          jQuery(".showlistbyMent").html(listhtml);
        } else {
          jQuery(".show_students").css("display", "none");
          jQuery(".head").html("");
          jQuery(".showlistbyMent").html("");
        }
      },
    });
  });

  jQuery("#chkAllSt").on("click", function () {
    if (this.checked) {
      jQuery(".chkSt").each(function () {
        this.checked = true;
      });
    }
  });

  jQuery("#unchkAllSt").on("click", function () {
    if (this.checked) {
      jQuery(".chkSt").each(function () {
        this.checked = false;
      });
    }
  });

  /*manage author code*/

  jQuery("#frmAddAuthor").validate({
    submitHandler: function () {
      jQuery("body").addClass("rtr-processing");
      var postdata =
        jQuery("#frmAddAuthor").serialize() +
        "&param=save_author_tr&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          show_msg(data.sts, data.msg);
          jQuery("#tmpl-author-list").html(data.arr.template);
          jQuery("#addAuthorTr").hide();
        } else {
        }
      });
    },
  });

  jQuery(".add_author_tr").on("click", function () {
    var modalID = "#addAuthorTr";
    jQuery(modalID).show();
    jQuery(modalID + " .modal-title").html("Add Author...");
    jQuery(modalID + " .modal-body input").val("");
    jQuery(modalID + " .defaultuploadimg").val("Upload Image");
    jQuery(modalID + " .uploadCourseImage").html("");
    jQuery(modalID + " #txtAbout").val("");
  });

  jQuery(document).on("click", ".editImgAction", function () {
    $(".uploadCourseImage").html("");
    $(".defaultCourseImgUrl").val("");
  });

  jQuery(document).on("click", ".btn-edit-author", function () {
    var dataid = jQuery(this).attr("data-id");
    var modalID = "#addAuthorTr";   
    jQuery(modalID).show();
    // alert("hii");
    jQuery(modalID + " .modal-title").html("Edit Author...");
    jQuery(modalID + " #txt_type").val("update");
    jQuery(modalID + " #txt_id").val(dataid);
    var closestTrRow = jQuery(this).closest("tr.data-row");
    jQuery(modalID + " #txtName").val(closestTrRow.find("td.data-name").text());
    jQuery(modalID + " #txtEmail").val(
      closestTrRow.find("td.data-email").text()
    );
    jQuery(modalID + " #txtPost").val(closestTrRow.find("td.data-post").text());
    jQuery(modalID + " #txtWeb").val(jQuery(this).attr("data-web"));
    jQuery(modalID + " #txtFacebook").val(jQuery(this).attr("data-fburl"));
    jQuery(modalID + " #txtPhone").val(
      closestTrRow.find("td.data-phone").text()
    );
    jQuery(modalID + " #txtAbout").val(jQuery(this).attr("data-about"));
    jQuery(modalID).show();
    var image = jQuery(this).data("imgae");

    if (image !== "") {
      jQuery(modalID + " .uploadCourseImage").html(
        "<div class='editImg'><a class='editImgAction' href='javascript:void(0);'><i class='fa'>&#xf00d;</i></a></div><img src='" +
          image +
          "'/>"
      );
      jQuery(modalID + " .defaultCourseImgUrl").val(image);
    }
  });

  jQuery(document).on("click", ".btn-del-author", function () {
    var conf = confirm("Are you sure want to delete?");
    if (conf) {
      jQuery("body").addClass("rtr-processing");
      var dataid = jQuery(this).attr("data-id");
      var postdata =
        "delid=" + dataid + "&param=delete_author_tr&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          show_msg(data.sts, data.msg);
          jQuery("#tmpl-author-list").html(data.arr.template);
          jQuery("#addAuthorTr").hide();
          setTimeout(function () {
            window.location.reload();
          }, 1200);
        } else {
          show_msg(data.sts, data.msg);
        }
      });
    }
  });

  jQuery("#frmAddCatgeory").validate({
    submitHandler: function () {
      jQuery("body").addClass("rtr-processing");
      var postdata =
        jQuery("#frmAddCatgeory").serialize() +
        "&param=add_category_tr&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          show_msg(data.sts, data.msg);
          jQuery("#tmpl-category-list").html(data.arr.template);
          jQuery("#addCategoryTr").hide();
          setTimeout(function () {
            window.location.reload();
          }, 1200);
        } else {
          show_msg(data.sts, data.msg);
        }
      });
    },
  });

  jQuery("#frmAddSubcatgeory").validate({
    submitHandler: function () {
      jQuery("body").addClass("rtr-processing");
      var postdata =
        jQuery("#frmAddSubcatgeory").serialize() +
        "&param=add_subcategory_tr&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          show_msg(data.sts, data.msg);
          jQuery("#addSubcategoryTr").hide();
          setTimeout(function () {
            window.location.reload();
          }, 1200);
        } else {
          show_msg(data.sts, data.msg);
        }
      });
    },
  });

  jQuery(document).on("click", ".btn-del-category", function () {
    var conf = confirm("Are you sure want to delete?");
    if (conf) {
      jQuery("body").addClass("rtr-processing");
      var dataid = jQuery(this).attr("data-id");
      var postdata =
        "id=" + dataid + "&param=del_subcategory_tr&action=training_lib"+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        jQuery("body").removeClass("rtr-processing");
        var data = jQuery.parseJSON(response);
        if (data.sts == 1) {
          show_msg(data.sts, data.msg);
          setTimeout(function () {
            window.location.reload();
          }, 1200);
        } else {
          show_msg(data.sts, data.msg);
        }
      });
    }
  });

  // loading subcategories...

  var href = location.href;

  if (trdata.is_admin == "1") {
    var page = href.split("?");
    var pageType = page[1].split("=");
    var stringVal = pageType[1],
      substring = "new_course";
    if (stringVal.indexOf(substring) !== -1) {
      var category_id = jQuery("#slct_category").val();
      //loadCategory(category_id);
    }
  }

  jQuery("#slct_category").on("change", function () {
    var category_id = jQuery(this).val();

    if (category_id > 0) {
      loadCategory(category_id);
    } else {
      alert("Please select a category");
      jQuery("#slct_subcategory").html(
        '<option value="-1"> -- choose subcategory -- </option>'
      );
    }
  });

  jQuery(document).on("click", ".taghover", function () {
    var conf = confirm("Are you sure want to delete?");
    if (conf) {
      var id = jQuery(this).attr("data-val");
      var catid = jQuery(this).attr("data-id");
      var postdata =
        "param=remove_subcategory&action=training_lib&subcat=" +
        id +
        "&category=" +
        catid+"&nonce=" + rtr_script_data.nonce;
      jQuery.post(ajaxurl, postdata, function (response) {
        var data = jQuery.parseJSON(response);
        // removed from db table as well
        if (data.sts == 0) {
          show_msg(data.sts, data.msg);
        } else {
          show_msg(data.sts, data.msg);
          jQuery("span.taghover[data-val='" + id + "']").remove();
        }
      });
    }
  });
});

function loadCategory(category_id) {
  // means valid category
  var postdata =
    "param=get_subcategories&action=training_lib&catid=" + category_id+"&nonce=" + rtr_script_data.nonce;
  jQuery.post(ajaxurl, postdata, function (response) {
    var data = jQuery.parseJSON(response);
    var html = "";
    if (data.arr.categories.length > 0) {
      data.arr.categories.map(function (item) {
        html += "<option value='" + item + "'>" + item + "</option>";
      });
      jQuery("#slct_subcategory").html(html);
    } else {
      jQuery("#slct_subcategory").html(
        '<option value="-1"> -- choose subcategory -- </option>'
      );
    }
  });
}

jQuery(document).ready(function () {
  jQuery(".bs-modal")
    .on("show.bs.modal", function (e) {
      jQuery("body").addClass("bs-modal-open");
    })
    .on("hidden.bs.modal", function (e) {
      jQuery("body").removeClass("bs-modal-open");
    });
});

function isValidUrl(url) {
  var myVariable = url;
  if (
    /^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(
      myVariable
    )
  ) {
    return 1;
  } else {
    return 0;
  }
}

//Code to Hide the modals while clicking on the close buttons
jQuery(document).on(
  "click",
  ".rtr-close, .rtr-lesson-dialog-cancel, .rtr-movemodal-cancel, .rtr-reorder-cancel, .rtr-video-dialog-cancel, .rtr-note-dialog-cancel, .rtr-help-dialog-cancel, .rtr-download-dialog-cancel  ",
  function () {
    if (jQuery("#addAuthorTr").is(":visible")) {
      jQuery("#addAuthorTr").hide();
    }
    if (jQuery("#reordermodal").is(":visible")) {
      jQuery("#reordermodal").hide();
    }
    if (jQuery("#addCategoryTr").is(":visible")) {
      jQuery("#addCategoryTr").hide();
    }
    if (jQuery("#addSubcategoryTr").is(":visible")) {
      jQuery("#addSubcategoryTr").hide();
    }
    if (jQuery("#updateCategoryTr").is(":visible")) {
      jQuery("#updateCategoryTr").hide();
    }
    if (jQuery("#updateSubcategoryTr").is(":visible")) {
      jQuery("#updateSubcategoryTr").hide();
    }
    if (jQuery("#lesson_dialog").is(":visible")) {
      jQuery("#lesson_dialog").hide();
      window.location.reload();
    }
    if (jQuery("#movemodal").is(":visible")) {
      jQuery("#movemodal").hide();
    }
    if (jQuery("#video_dialog").is(":visible")) {
      jQuery("#video_dialog").hide();
    }

    if (jQuery("#note_dialog").is(":visible")) {
      jQuery("#note_dialog").hide();
      window.location.reload();
    }

    if (jQuery("#help_dialog").is(":visible")) {
      jQuery("#help_dialog").hide();
      window.location.reload();
    }

    if (jQuery("#download_dialog").is(":visible")) {
      jQuery("#download_dialog").hide();
    }

    if (jQuery("#project_summitted").is(":visible")) {
      jQuery("#project_summitted").hide();
    }
    if (jQuery("#subPrjModal").is(":visible")) {
      jQuery("#subPrjModal").hide();
    }

    if (jQuery("#image_dialog").is(":visible")) {
      jQuery("#image_dialog").hide();
    }

    if (jQuery("#enrolled_dialog").is(":visible")) {
      jQuery("#enrolled_dialog").hide();
    }
  }
);

//To show the category modals on the click of the buttons
jQuery(document).on("click", ".add_category_tr", function () {
  jQuery("#addCategoryTr").show();
});

//To show the subcategory modal on the click of the buttons
jQuery(document).on("click", ".add_subcategory_tr", function () {
  jQuery("#addSubcategoryTr").show();
});
jQuery(document).on("click", ".rtr-create-exercise", function () {
  jQuery("#lesson_dialog").show();
});

jQuery(document).on("click", ".rtr-notify-close", function () {
  if (jQuery("#rtr-notify-alert").is(":visible")) {
    jQuery("#rtr-notify-alert").hide();
  }
});

//Closing the image modal on close button click.
jQuery(document).on("click", ".rtr-image-dialog-cancel", function () {
  jQuery("#image_dialog").hide();
});

//Displaying the subcategory on click of the category for frontend course filter.
jQuery(document).on("click", ".rtr-list-group-item", function (e) {
  var submenu = jQuery(this).next();
  var submenuId = submenu.data("id");
  jQuery(".rtr-list-group")
    .find(".rtr-list-group-submenu")
    .each(function (index, item) {
      var itemId = jQuery(item).data("id");
      if (jQuery(item).css("display") !== "none" && itemId !== submenuId) {
        jQuery(item).css("display", "none");
      }
    });
  if (jQuery(this).next().css("display") == "none") {
    submenu.show();
  } else {
    submenu.hide();
  }
});

//Displaying mycourse section on the button click
jQuery(document).on("click", "#rtr-mycourse-btn", function (e) {
  jQuery(this).parent().addClass("active");
  jQuery("#rtr-allcourse-btn").parent().removeClass("active");
  jQuery("#mycourse").addClass("active in");
  jQuery("#allcourse").hide();
  jQuery("#allcourse").removeClass("active in");
  jQuery("#mycourse").show();
});
//Displaying allcourse section on the button click
jQuery(document).on("click", "#rtr-allcourse-btn", function (e) {
  jQuery(this).parent().addClass("active");
  jQuery("#rtr-mycourse-btn").parent().removeClass("active");
  jQuery("#allcourse").show();
  jQuery("#mycourse").hide();
});

//Custom function for toggeling modal
function toggleModal(selector) {
  var modal = jQuery(selector);
  if (modal.css("display") === "none") {
    modal.css("display", "block"); // Show the modal
  } else {
    modal.css("display", "none"); // Hide the modal
  }
}

$(window).on("scroll", function () {
  // alert("hiiiiii")
  // Get the position of the section
  var allcourse_section = $("#allcourse");
  var mycourse_section = $("#mycourse");
  if (allcourse_section.length && allcourse_section.is(":visible")) {  
    var section_offset = allcourse_section.offset().top;
    var scroll_position = $(window).scrollTop();

    // Check if the section is touching the top of the screen
    if (
      scroll_position >= section_offset &&
      scroll_position < section_offset + allcourse_section.height()
    ) {     
      $("#training-ui-container").addClass("sidebar-filter");
    } else {
      $("#training-ui-container").removeClass("sidebar-filter");
    }
  }

  if (mycourse_section.length && mycourse_section.is(":visible")) { 
    var section_offset = mycourse_section.offset().top;
    var scroll_position = $(window).scrollTop();

    // Check if the section is touching the top of the screen
    if (
      scroll_position >= section_offset &&
      scroll_position < section_offset + mycourse_section.height()
    ) {
      $("#training-ui-container").addClass("sidebar-filter");
    } else {
      $("#training-ui-container").removeClass("sidebar-filter");
    }
  }
});
