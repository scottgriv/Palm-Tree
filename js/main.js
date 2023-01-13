/* Javascript/jQuery File */

// Declare Variables
var f_sort_first_name = 1; // Default Sort for First Name Field
var f_sort_last_name = 1; // Default Sort for Last Name Field
var f_sort_email = 1; // Default Sort for Email Field
var f_sort_phone = 1; // Default Sort for Phone Field
var f_sort_created = 1; // Default Sort for Created Date/Time Field
var counter; // Counter for New Customer
var isButtonSubmit = false; // Submit Button from Customer Notes
var cust_notes; // Customer Notes
var cust_id; // Customer ID

// Copy Variables to Clipboard Functi don
function copy_to_clipboard(event, element) {
  event.preventDefault();
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  window.alert("Text copied to clipboard");
}

function resetOriginalData() {
  originalData = $("#data_table tbody tr").get();
}

//Search for Customers
const searchCustomers = () => {
  // Check if Counter is NOT Null
  if (counter != null) {
    alert(
      "You cannot perform a Search when you're in the process of creating a new Customer.\n\rPlease delete the bottom row and try again."
    );
    return;
  }

  const columns = [
    { name: "Customer ID", index: 0, isFilter: false },
    { name: "First Name", index: 1, isFilter: true },
    { name: "Last Name", index: 2, isFilter: true },
    { name: "Email", index: 3, isFilter: true },
    { name: "Phone", index: 4, isFilter: true },
  ];

  const filterColumns = columns.filter((c) => c.isFilter).map((c) => c.index);
  const trs = document.querySelectorAll(`#data_table tr:not(.header)`);
  const filter = document.querySelector("#myInput").value;
  const regex = new RegExp(escape(filter), "i");
  const isFoundInTds = (td) => regex.test(td.innerHTML);
  const isFound = (childrenArr) => childrenArr.some(isFoundInTds);
  const setTrStyleDisplay = ({ style, children }) => {
    style.display = isFound([
      ...filterColumns.map((c) => children[c]), // <-- filter Columns
    ])
      ? ""
      : "none";
  };

  trs.forEach(setTrStyleDisplay);
};

// Export Table to CSV
function download_table_as_csv(table_id, separator = ",") {
  // Check if Counter is NOT Null
  if (counter != null) {
    alert(
      "You cannot export data when you're in the process of creating a new Customer.\n\rPlease delete the bottom row and try again."
    );
    return;
  }

  // Select rows from table_id
  var rows = document.querySelectorAll("table#" + table_id + " tr");

  // Construct csv
  var csv = [];
  for (var i = 0; i < rows.length; i++) {
    var row = [],
      cols = rows[i].querySelectorAll("td, th");
    for (var j = 0; j < cols.length; j++) {
      // Clean innertext to remove multiple spaces and jumpline (break csv)]
      if (j <= 7) {
        var data = cols[j].innerText
          .replace(/(\r\n|\n|\r)/gm, "")
          .replace(/(\s\s)/gm, " ");
        // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
        data = data.replace(/"/g, '""');
        // Push escaped string
        row.push('"' + data + '"');
      }
    }
    csv.push(row.join(separator));
  }

  var csv_string = csv.join("\n");

  // Download the File
  var filename =
    "Palm_Tree_Customers_" + new Date().toLocaleDateString() + ".csv";
  var link = document.createElement("a");
  link.style.display = "none";
  link.setAttribute("target", "_blank");
  link.setAttribute(
    "href",
    "data:text/csv;charset=utf-8," + encodeURIComponent(csv_string)
  );
  link.setAttribute("download", filename);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// Change the Logo when a new Logo is Uploaded
$("#files").change(function () {
  filename = this.files[0].name;
  console.log(filename);
});
var loadFile = function (event) {
  var output = document.getElementById("output");
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function () {
    URL.revokeObjectURL(output.src); // Free Up Memory
  };
};

// Send Single Email
function send_single_email(box) {
  var cust_id = box.getAttribute("value");
  console.log(cust_id); // Output the Customer ID value to the console

  if (!box.checked) {
    // The Checkbox is checked
    if (
      confirm(
        "Are you sure you want to uncheck the sent flag?\n\rYou've already sent an email to this customer, sending too many similar emails might result in the email being marked as Spam."
      )
    ) {
      var flag = 0;
    } else {
      // The checkbox is unchecked
      $(box).prop("checked", true);

      return;
    }
  } else {
    var flag = 1;
  }

  // AJAX Request/Respoonse
  $.ajax({
    cache: false,
    type: "POST",
    url: "send_email.php",
    data: "flag=" + flag + "&cust_id=" + cust_id + "&command=sendEmail",
    dataType: "HTML",
    success: function (data) {
      if (flag == 1) {
        // Set the flag values
        $(box).prop("checked", true);

        // Set the hidden field values
        $(box).closest("tr").find(".hiddenFlag").text("Sent");

        alert("Successfully Sent Email!");
      } else {
        // Set the flag values
        $(box).prop("checked", false);

        // Set the hidden field values
        $(box).closest("tr").find(".hiddenFlag").text("Not Sent");
      }
      resetOriginalData();

      console.log(data);
    },
    error: function (error) {
      // Handle Error
      if (flag == 1) {
        // Set the flag values
        $(box).prop("checked", false);

        // Set the hidden field values
        $(box).closest("tr").find(".hiddenFlag").text("Not Sent");
      } else {
        // Set the flag values
        $(box).prop("checked", true);

        // Set the hidden field values
        $(box).closest("tr").find(".hiddenFlag").text("Sent");
      }

      var response = JSON.parse(error.responseText);
      console.log(response.error); // Output the value of the error

      var error = response.error.replace("<br>", "\n\r");

      // Display the error in the alert
      alert(error);
    },
  });
}

// Send Mass Emails
function send_mass_email(box) {
  if (
    !confirm(
      "Are you sure you want to send emails to ALL your Customers at once?\n\rPlease wait for the Success prompt when sending.\n\rNOTE: This may take some time to complete."
    )
  ) {
    box.checked = true;
    console.log("Cancel Clicked");
  } else {
    console.log("OK Clicked");

    // AJAX Request/Respoonse
    $.ajax({
      cache: false,
      type: "POST",
      url: "send_email.php",
      data: "command=sendMassEmail",
      dataType: "HTML",
      success: function (data) {
        alert("Successfully Sent Mass Emails!");
        resetOriginalData();

        console.log(data);
      },
      error: function (error) {
        // Handle Error
        var response = JSON.parse(error.responseText);
        console.log(response.error); // Output the value of the error
        var error = response.error.replace("<br>", "\n\r");

        alert(error);
      },
    });
  }
}

// Update Email Flags
function update_email_flags(box) {
  if (!box.checked) {
    if (
      !confirm(
        "Are you sure you want to Remove ALL Send Email Flags for ALL Customers?\n\rWARNING! This cannot be undone!"
      )
    ) {
      box.checked = true;
      console.log("Cancel Clicked");
    } else {
      console.log("OK Clicked");
      var resetFlags = "resetFlags";

      // AJAX Request/Respoonse
      $.ajax({
        cache: false,
        type: "POST",
        url: "send_email.php",
        data: "command=updateEmailFlags",
        dataType: "HTML",
        success: function (data) {
          alert("Successfully Removed Send Flags!");
          console.log(data);
          resetOriginalData();
        },
        error: function (error) {
          // Handle Error
          var response = JSON.parse(error.responseText);
          console.log(response.error); // Output the value of the error
          var error = response.error.replace("<br>", "\n\r");

          alert(error);
        },
      });
    }
  }
}

// jQuery Load Document
$(document).ready(function () {
  // Declare Variables
  var originalData = $("#data_table tbody tr").get(); // Store Original Table Data
  const scrollable = $(".scrollable"); // Scrollable Div Tag
  const scrollUpButton = $("#scrollUp"); // Scroll Up Button
  const scrollDownButton = $("#scrollDown"); // Scroll Down Button

  // Set Customer Date/Time Created to Read Only
  $("#cust_created").prop("readonly", true);

  // Email Validation Function
  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

  //Display Customer Note Popup Menu
  $("table").on("click", ".custNotes", function (event) {
    // Check if Counter is NOT Null
    if (counter != null) {
      alert(
        "You cannot edit Customer Notes when you're in the process of creating a new Customer.\n\rPlease delete the bottom row and try again."
      );
      return;
    }

    var cust_id = $(this).data("cust-id");

    $("#notes_id").val(cust_id);

    // Show the overlay and popup elements
    $(this)
      .siblings(".popup-container")
      .find("#overlay")
      .css("display", "block");
    $(this).siblings(".popup-container").find("#popup").css("display", "block");
  });

  // Set up event listeners for the buttons
  // Scroll Up Button Clicked
  scrollUpButton.click(() => {
    scrollUp();
  });

  // Scroll Down Button Clicked
  scrollDownButton.click(() => {
    scrollDown();
  });

  // Scroll to the Top of the Table
  function scrollUp() {
    console.log("Scroll Up");
    scrollable.animate(
      {
        scrollTop: 0,
      },
      "slow"
    );
  }

  // Scroll to the Top of the Table
  function scrollDown() {
    console.log("Scroll Down");
    scrollable.animate(
      {
        scrollTop: scrollable.prop("scrollHeight"),
      },
      "slow"
    );
  }

  // Cancel Notes - Button Clicked
  $("table").on("click", ".cancelNotes", function (event) {
    var cancelTextArea = $(this).closest("tr").find(".custNotesText").val();

    // Show the overlay and popup elements
    $(this).parents(".popup-container").find("#overlay").css("display", "none");
    $(this).parents(".popup-container").find("#popup").css("display", "none");
  });

  // Submit Notes - Button Clicked
  $("table").on("click", ".submitNotes", function (event) {
    // Set the flag to true
    isButtonSubmit = true;

    // Get the specific textarea element that was clicked
    var textarea = $(this).closest("tr").find(".custNotesText");

    // Get the value of the textarea element
    cust_notes = textarea.val();

    // Set the hidden field values
    $(this).closest("tr").find(".hiddenNotes").text(cust_notes);

    // Get the value of the data-cust-id attribute of the textarea element
    cust_id = textarea.data("cust-id");

    console.log("cust_id:", cust_id);
    console.log("cust_notes:", cust_notes);

    // Use the submit method on the form element to submit the form
    var row = $(this).parents("tr");

    // Find the button with the class "custNotes" within the row
    var custNotesButton = row.find(".custNotes");

    if (cust_notes != "") {
      custNotesButton.css("background-image", "url(img/notes_exist.png)");
    } else {
      custNotesButton.css("background-image", "url(img/notes.png)");
    }

    $("#custAddForm").submit();

    $(this).parents(".popup-container").find("#overlay").css("display", "none");
    $(this).parents(".popup-container").find("#popup").css("display", "none");
  });

  // Create New Customer & Update Customer Notes
  $("#custAddForm").on("submit", function (e) {
    e.preventDefault();

    // Update Customer Notes
    if (isButtonSubmit) {
      var cust_notes_saved = cust_notes;
      // AJAX Request/Respoonse
      $.ajax({
        cache: false,
        type: "POST",
        url: "commands.php",
        data:
          "cust_id=" +
          cust_id +
          "&cust_notes=" +
          encodeURIComponent(cust_notes) +
          "&command=updateCustomerNotes",
        dataType: "HTML",
        success: function (data) {
          resetOriginalData();
          console.log(data);
        },
        error: function (error) {
          // Handle Error
          var response = JSON.parse(error.responseText);
          console.log(response.error); // Output the value of the error
          var error = response.error.replace("<br>", "\n\r");

          alert(error);
        },
      });

      // Create New Customer
    } else {
      // Get the value of each input field in the form
      var cust_first_name_new = $(this).find("#cust_first_name_new").val();
      var cust_last_name_new = $(this).find("#cust_last_name_new").val();
      var cust_email_new = $(this).find("#cust_email_new").val();
      var cust_phone_new = $(this).find("#cust_phone_new").val();

      // Check if any of the values are blank
      if (
        cust_first_name_new === "" &&
        cust_last_name_new === "" &&
        cust_email_new === "" &&
        cust_phone_new === ""
      ) {
        return;
      }

      // Check if Email is Missing
      if (cust_email_new === "") {
        if (
          confirm(
            "You're missing an Email for your new Customer, this is the most important piece of information you can provide.\n\rPress OK to create the record anyways or Cancel to back out."
          )
        ) {
        } else {
          return;
        }
      }

      // Check if Email is in a Valid Format
      if (cust_email_new != "" && !isEmail(cust_email_new)) {
        if (
          confirm(
            "Your email format is Invalid!\n\rIt should be in a format of name@domain.com."
          )
        ) {
        } else {
          return;
        }
      }

      // Check if Email Already Exists
      if (valueExistsInColumn(cust_email_new, 3) && cust_email_new != "") {
        if (
          confirm(
            "" +
              cust_email_new +
              " already exists in the table!\n\rPress OK to create the record anyways or Cancel to back out."
          )
        ) {
        } else {
          return;
        }
      }

      $(this).closest("tr").remove();

      // AJAX Request/Respoonse
      $.ajax({
        cache: false,
        type: "POST",
        url: "commands.php",
        data:
          "cust_first_name_new=" +
          cust_first_name_new +
          "&cust_last_name_new=" +
          cust_last_name_new +
          "&cust_email_new=" +
          cust_email_new +
          "&cust_phone_new=" +
          cust_phone_new +
          "&command=createCustomer",
        dataType: "HTML",
        success: function (data) {
          $("#custAdd").closest("tr").remove();
          counter = null;

          json = eval("(" + data + ")");

          console.log(json);

          // Create a New Customer Row based on the newly inserted data
          var newRow =
            '<tr id="' +
            json.cust_id +
            '">' +
            '<td style="display: none;"><span class="tabledit-span tabledit-identifier">' +
            json.cust_id.toString() +
            '</span><input class="tabledit-input tabledit-identifier" type="hidden" name="cust_id" value="' +
            json.cust_id.toString() +
            '" disabled=""></td>' +
            '<td class="tabledit-view-mode" style="cursor: pointer;"><span class="tabledit-span" style="display: inline;">' +
            json.cust_first_name +
            '</span><input class="tabledit-input form-control input-sm" type="text" name="cust_first_name" value="' +
            json.cust_first_name +
            '" style="display: none;" disabled=""></td>' +
            '<td class="tabledit-view-mode" style="cursor: pointer;"><span class="tabledit-span" style="display: inline;">' +
            json.cust_last_name +
            '</span><input class="tabledit-input form-control input-sm" type="text" name="cust_last_name" value="' +
            json.cust_last_name +
            '" style="display: none;" disabled=""></td>' +
            '<td class="tabledit-view-mode" style="cursor: pointer;"><span class="tabledit-span" style="display: inline;">' +
            json.cust_email +
            '</span><input class="tabledit-input form-control input-sm" type="text" name="cust_email" value="' +
            json.cust_email +
            '" style="display: none;" disabled=""></td>' +
            '<td class="tabledit-view-mode" style="cursor: pointer;"><span class="tabledit-span" style="display: inline;">' +
            json.cust_phone +
            '</span><input class="tabledit-input form-control input-sm" type="text" name="cust_phone" value="' +
            json.cust_phone +
            '" style="display: none;" disabled=""></td>' +
            '<td>' +
            json.cust_created +
            "</td>" +
            '<td class="hiddenFlag" style="display:none;">Not Sent</td>' +
            '<td class="hiddenNotes" style="display:none;"></td>' +
            '<td style="text-align:center; vertical-align:middle" title="Checked=Email Sent, Unchecked=Email Not Sent"><input name="cust_id" id="flag" value="' +
            json.cust_id +
            '" type="checkbox" onchange="send_single_email(this)"></td>' +
            '<td style="text-align:center; vertical-align:middle">' +
            '<button id="custNotes" class="custNotes" type="button" title="Customer Notes"></button>' +
            '<div class="popup-container">' +
            '<div id="overlay" style="display:none;"></div>' +
            '<div id="popup" style="display:none;">' +
            '<textarea id="custNotesText" class="custNotesText" name="freeform" rows="20" cols="1" style="text-align: left;" placeholder="Log some Notes about the Customer here..." data-cust-id="' +
            json.cust_id.toString() +
            '"></textarea>' +
            '<input type="hidden" id="notes_id" name="notes_id" value="' +
            json.cust_id.toString() +
            '">' +
            '<button id="submitNotes" class="submitNotes" type="button" style="background:#228B22;color:white;width:269px;height:35px;border:0;" title="Save Customer Notes">Save Notes</button>' +
            '<button id="cancelNotes" class="cancelNotes" type="button" style="background:#FF0000;color:white;width:270px;height:35px;border:0;" title="Cancel Entering Notes">Cancel</button>' +
            "</div>" +
            "</div>" +
            '<td style="text-align:center; vertical-align:middle">' +
            '<input id="custDelete" type="button" title="Delete Customer">' +
            "</td>" +
            "</tr>";

          $("#data_table").append(newRow);

          //Update the originalData variable with the most current table information.
          resetOriginalData();

          alert("Successfully Created Customer!");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          // Handle Error
          var error = JSON.parse(jqXHR.responseText);
          alert(error);
        },
      });
    }
    // Reset the flag to false
    isButtonSubmit = false;
    cust_notes = null;
    cust_id = null;
  });

  $("#insertRow").on("click", function (event) {
    if (counter != null) {
      alert(
        "You currently in the process of creating a new Customer.\n\rYou can only create one Customer at a time."
      );
      return;
    }

    resetSort();

    counter = 1;

    event.preventDefault();

    var newRow = $("<tr>");
    var cols = "";

    // Table columns
    cols +=
      '<td style="display: none;><span class="tabledit-span tabledit-identifier">' +
      counter +
      '</span><input class="tabledit-input tabledit-identifier" type="hidden" id="cust_id_new" name="cust_id_new" value="' +
      counter +
      '" disabled=""></td>';
    cols +=
      '<td class="tabledit-view-mode" style="cursor: pointer;"><input style="width: 95px;" class="form-control rounded-0" type="text" id="cust_first_name_new" name="cust_first_name_new" placeholder="First Name"></td>';
    cols +=
      '<td class="tabledit-view-mode" style="cursor: pointer;"><input style="width: 95px;" class="form-control rounded-0" type="text" id="cust_last_name_new" name="cust_last_name_new" placeholder="Last Name"></td>';
    cols +=
      '<td class="tabledit-view-mode" style="cursor: pointer;"><input style="width: 180px;" class="form-control rounded-0" type="text" id="cust_email_new" name="cust_email_new" placeholder="Email"></td>';
    cols +=
      '<td class="tabledit-view-mode" style="cursor: pointer;"><input style="width: 120px;" class="form-control rounded-0" type="text" id="cust_phone_new" name="cust_phone_new" placeholder="Phone"></td>';
    cols +=
      '<td class="tabledit-view-mode" style="cursor: pointer;"><span class="tabledit-span"></span></td>';
    cols +=
      '<td class="tabledit-view-mode" style="cursor: pointer;"><span class="tabledit-span"></span></td>';
    cols +=
      '<td style="text-align:center; vertical-align:middle"><input style="display:none" id="custAdd" name="custAdd" type="submit" title="Add Customer"></td>';
    cols +=
      '<td style="text-align:center; vertical-align:middle"><button id="custDeleteNew" type="button" title="Delete Customer"></button></td>';

    // Insert the columns inside a row
    newRow.append(cols);

    // Insert the row inside a table
    $("table").append(newRow);

    // Increase counter after each row insertion
    counter++;

    // Get the div element
    var div = $(".scrollable");

    // Get the height of the table
    var tableHeight = div.find("#data_table").height();

    // Scroll to the bottom of the table
    div.scrollTop(tableHeight);
  });

  // Remove New Customer Row when Delete Clicked
  $("table").on("click", "#custDeleteNew", function (event) {
    $(this).closest("tr").remove();
    counter = null;
  });

  // Remove Customer Row and Delete it from the Database when Delete Clicked
  $("table").on("click", "#custDelete", function (event) {
    var cust_id = $(this).closest("tr").attr("id");

    var cust_first_name = $(this)
      .closest("tr")
      .find("[name='cust_first_name']")
      .val();
    var cust_last_name = $(this)
      .closest("tr")
      .find("[name='cust_last_name']")
      .val();

    if (counter != null) {
      alert(
        "You cannot delete Customers when you're in the process of creating a new Customer.\n\rPlease delete the bottom row and try again."
      );
      return;
    }

    if (
      confirm(
        "Are you sure you want to delete Customer " +
          cust_first_name +
          " " +
          cust_last_name +
          "?\n\rPress OK to delete the record anyways or Cancel to back out."
      )
    ) {
      $(this).closest("tr").remove();

      // AJAX Request/Respoonse
      $.ajax({
        cache: false,
        type: "POST",
        url: "commands.php",
        data: "cust_id=" + cust_id + "&command=deleteCustomer",
        dataType: "HTML",
        success: function (data) {
          alert("Successfully Deleted Customer!");
          resetOriginalData();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          var error = JSON.parse(jqXHR.responseText);
          alert(error);
        },
      });
    } else {
      return;
    }
  });

  // Sort Table Function
  function sortTable(f, n) {
    var rows = $("#data_table tbody  tr").get();

    rows.sort(function (a, b) {
      var A = getVal(a);
      var B = getVal(b);

      if (A < B) {
        return -1 * f;
      }
      if (A > B) {
        return 1 * f;
      }
      return 0;
    });

    function getVal(elm) {
      var v = $(elm).children("td").eq(n).text().toUpperCase();
      if ($.isNumeric(v)) {
        v = parseInt(v, 10);
      }
      return v;
    }

    $.each(rows, function (index, row) {
      $("#data_table").children("tbody").append(row);
    });
  }

  // Check if Value Exists in Column
  function valueExistsInColumn(value, columnIndex) {
    var exists = false;

    // Select the table rows
    $("#data_table tr").each(function () {
      // Get the value of the cell in the specified column
      var cellValue = $(this).find("td").eq(columnIndex).text();

      // Compare the cell value to the value passed to the function
      if (cellValue == value) {
        exists = true;
        return false; // Break out of the loopxx
      }
    });

    return exists;
  }

  // Reset Sort
  function resetSort() {
    $("#data_table tbody").empty(); // clear the current table data
    $.each(originalData, function (index, row) {
      $("#data_table tbody").append(row); // add the original data back to the table
    });

    $("#sort_first_name").removeClass("headerSortDown");
    $("#sort_first_name").addClass("headerSortUp");

    $("#sort_last_name").removeClass("headerSortDown");
    $("#sort_last_name").addClass("headerSortUp");

    $("#sort_email").removeClass("headerSortDown");
    $("#sort_email").addClass("headerSortUp");

    $("#sort_phone").removeClass("headerSortDown");
    $("#sort_phone").addClass("headerSortUp");

    $("#sort_created").removeClass("headerSortDown");
    $("#sort_created").addClass("headerSortUp");
  }

  // Sort First Name - Table Header Click
  $("#sort_first_name").click(function () {
    if (counter == null) {
      f_sort_first_name *= -1;
      var n = $(this).prevAll().length;

      $(this).toggleClass("headerSortUp");
      $(this).toggleClass("headerSortDown");

      sortTable(f_sort_first_name, n);
      scrollUp();
    }
  });

  // Sort Last Name - Table Header Click
  $("#sort_last_name").click(function () {
    if (counter == null) {
      f_sort_last_name *= -1;
      var n = $(this).prevAll().length;

      $(this).toggleClass("headerSortUp");
      $(this).toggleClass("headerSortDown");

      sortTable(f_sort_last_name, n);
      scrollUp();
    }
  });

  // Sort Email - Table Header Click
  $("#sort_email").click(function () {
    if (counter == null) {
      f_sort_email *= -1;
      var n = $(this).prevAll().length;

      $(this).toggleClass("headerSortUp");
      $(this).toggleClass("headerSortDown");

      sortTable(f_sort_email, n);
      scrollUp();
    }
  });

  // Sort Phone - Table Header Click
  $("#sort_phone").click(function () {
    if (counter == null) {
      f_sort_phone *= -1;
      var n = $(this).prevAll().length;

      $(this).toggleClass("headerSortUp");
      $(this).toggleClass("headerSortDown");

      sortTable(f_sort_phone, n);
      scrollUp();
    }
  });

  // Sort Created Date/Time - Table Header Click
  $("#sort_created").click(function () {
    if (counter == null) {
      f_sort_created *= -1;
      var n = $(this).prevAll().length;

      $(this).toggleClass("headerSortUp");
      $(this).toggleClass("headerSortDown");

      sortTable(f_sort_created, n);
      scrollUp();
    }
  });
});
