document.getElementById("phone").addEventListener("input", function () {
    let value = this.value.replace(/\D/g, "");

    if (value.startsWith("8")) {
        value = "7" + value.slice(1);
    } else if (!value.startsWith("7")) {
        value = "7" + value;
    }

    value = value.slice(0, 11);

    let formattedNumber = `+7`;
    if (value.length > 1) formattedNumber += ` (${value.slice(1, 4)}`;
    if (value.length > 4) formattedNumber += `) ${value.slice(4, 7)}`;
    if (value.length > 7) formattedNumber += `-${value.slice(7, 9)}`;
    if (value.length > 9) formattedNumber += `-${value.slice(9, 11)}`;

    this.value = formattedNumber;
});

document.getElementById("age").addEventListener("input", function () {
    let min = 18;
    let max = 100;
    let value = this.value;
    if (!/^\d*$/.test(value)) {
        this.value = value.replace(/\D/g, "");
        return;
    }
    let numValue = parseInt(value, 10);
    if (!isNaN(numValue)) {
        if (numValue < min && value.length >= 2) {
            this.value = min;
        } else if (numValue > max) {
            this.value = max;
        }
    }
});

document.getElementById("name").addEventListener("input", function () {
    this.value = this.value.replace(/[^a-zA-Zа-яА-ЯёЁ\s]/g, "");
    if (this.value.length > 20) {
        this.value = this.value.slice(0, 20);
    }
});