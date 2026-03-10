function con(name, id, staff) {
  var reply = confirm("Confirm you want to remove "+name);
  if (reply) {
    window.location = "remove.php?id="+id+"&staff="+staff;
  }
}
