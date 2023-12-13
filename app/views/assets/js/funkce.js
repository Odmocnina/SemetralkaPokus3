function funkce(a) {
    if (a.length > 10) return a.substring(0, 10)+"...";
}

$("kontent p").text(function() {
    return funkce(this.innerHTML)
});