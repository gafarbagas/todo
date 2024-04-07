// init sortable
$(function () {
  $("#sortable").sortable();
});

// edit mode to handling mouseover and mouseout event at the same time in desc area
let editMode = false;

// show description input when mouseover
$(document).on('mouseover', '.col-desc', function () {
  if (editMode) {
    return;
  }
  $(this).find('.area-desc').show();
  $(this).find('.p-desc').hide();
});

// hide description input when mouseout
$(document).on('mouseout', '.col-desc', function () {
  if (editMode) {
    return;
  }
  $(this).find('.area-desc').hide();
  $(this).find('.p-desc').show();
});

$(document).ready(function () {

  // submit new task form
  $("#add-button").click(function () {
    let title = $("#title").val();
    let description = $("#description").val();
    if (title == '' || description == '') {
      if (title == '') {
        $("#title").next().text('Title is required');
      }
      if (description == '') {
        $("#description").next().text('Description is required');
      }
      return;
    }
    addTask();
  });

  // clear error message when user input
  $("#title").on('input', function () {
    if ($(this).val() != '') {
      $(this).next().text('');
    }
  });
  $("#description").on('input', function () {
    if ($(this).val() != '') {
      $(this).next().text('');
    }
  });

});

// hide description input when click outside
$(document).on('click', function (event) {
  editMode = false;
  if (!$(event.target).closest('.col-desc').length) {
    var areaDesc = $('.area-desc:visible');
    if (!areaDesc.length) {
      return;
    }
    var newDescription = areaDesc.val();
    var dataId = areaDesc.data('id');

    updateDescription(dataId, newDescription);

    $('.area-desc').hide();
    $('.p-desc').show();
  }
});

// show description input when click on description area
$(document).on('click', '.col-desc', function (event) {
  editMode = true;
  event.stopPropagation();
});

// update description when user input on description input
$(document).on('input', '.area-desc', function () {
  var newDescription = $(this).val();
  $(this).closest('.col-desc').find('.p-desc').text(newDescription);
});

// auto resize description input
$(document).on('focusin', '.area-desc', function () {
  var textarea = $(this);
  var lineHeight = parseInt(textarea.css('line-height'), 10);
  var currentRows = textarea.attr('rows');
  var newRows = Math.ceil(textarea.prop('scrollHeight') / lineHeight);
  
  if (newRows > currentRows) {
      textarea.attr('rows', newRows);
  }
});

// auto resize description input
$(document).on('focusout', '.area-desc', function () {
  var textarea = $(this);
  textarea.attr('rows', 1);
});

// delete task
$(document).on('click', '.delete', function () {
  var taskId = $(this).data('id');
  var request_data = {
    _method: "DELETE",
  };
  $(this).attr('disabled', true);
  $.ajax({
    url: "/tasks/" + taskId,
    method: "DELETE",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: request_data,
    success: function (response) {
      $('li[data-id="' + taskId + '"]').remove();
      if ($('#sortable li').length == 0) {
        $('#sortable').append('<h5 id="no-task" class="text-center">You have no task</h5>');
      } else {
        let lists = $("#sortable li");
        lists.each(function (index) {
          $(this).find('.card').removeClass('bg-body-secondary');
          if (index % 2 == 0) {
            $(this).find('.card').addClass('bg-body-secondary');
          }
        });
      }
    },
    error: function (response) {
    },
    finally: function () {
      $(this).attr('disabled', false);
    }
  })
});

// update completed status of task
$(document).on('click', '.is-completed', function () {
  var taskId = $(this).data('id');
  var request_data = {
    _method: "PUT",
    completed: $(this).is(':checked') ? 1 : 0,
  };
  $.ajax({
    url: "/tasks/" + taskId + "/completed",
    method: "PUT",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: request_data,
    success: function (response) {
      // change border color
      $('li[data-id="' + taskId + '"] .card').removeClass('border-left-success border-left-danger');
      $('li[data-id="' + taskId + '"] .card').addClass('border-left-' + (request_data.completed ? 'success' :
        'danger'));
    },
    error: function (response) {
    }
  })
});

// update sort order of tasks
$("#sortable").on("sortstop", function (event, ui) {
  var request_data = {
    _method: "PUT",
    tasks: [],
  };
  $('#sortable li').each(function () {
    request_data.tasks.push($(this).data('id'));
  });
  $.ajax({
    url: "/tasks/sort",
    method: "PUT",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: request_data,
    success: function (response) {
    },
    error: function (response) {
    }
  });
  let lists = $("#sortable li");
  lists.each(function (index) {
    $(this).find('.card').removeClass('bg-body-secondary');
    if (index % 2 == 0) {
      $(this).find('.card').addClass('bg-body-secondary');
    }
  });
});

// submit new task to server
function addTask() {
  var request_data = {
    title: $("#title").val(),
    description: $("#description").val(),
  };
  $.ajax({
    url: "/tasks",
    method: "POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: request_data,
    success: function (response) {
      appendToSortable(response.task);
      $("#staticBackdrop").modal('hide');
      $("#title").val('');
      $("#description").val('');
    },
    error: function (response) {
    }
  })
}

// append new task to sortable
function appendToSortable(taskData) {
  let count = $('#sortable li').length + 1;
  var newItem = $('<li>', {
    'data-id': taskData.id,
    class: 'list-group-item',
    html: '<div class="card border-left-danger ' + (count % 2 == 1 ? "bg-body-secondary" : '') +'">' +
      '<div class="card-body">' +
      '<div class="row align-items-center">' +
      '<div class="col-3">' +
      taskData.title +
      '</div>' +
      '<div class="col-7 col-desc">' +
      '<textarea name="description" data-id="' + taskData.id +
      '" rows="1" style="display: none" class="form-control area-desc">' + taskData.description +
      '</textarea>' +
      '<p class="p-desc m-0 p-0">' +
      taskData.description +
      '</p>' +
      '</div>' +
      '<div class="col-1 text-center">' +
      '<input type="checkbox" class="is-completed" data-id="' + taskData.id + '">' +
      '</div>' +
      '<div class="col-1 text-center">' +
      '<button href="javascript:;" class="btn btn-danger btn-sm delete" data-id="' + taskData.id + '">' +
      '<i class="fas fa-trash"></i>' +
      '</button>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>'
  });
  $('#sortable').append(newItem);
  $('#no-task').remove();
  // restart sortable
  $("#sortable").sortable('destroy');
  $("#sortable").sortable();
}

// update description of task
function updateDescription(taskId, newDescription) {
  var request_data = {
    description: newDescription,
  };
  $.ajax({
    url: "/tasks/" + taskId + "/description",
    method: "PUT",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: request_data,
    success: function (response) {
    },
    error: function (response) {
    }
  })
}