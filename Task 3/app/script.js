function validateEmail() {
    let emailInput = document.getElementById("email");
    let email = emailInput.value.toLowerCase();

    return fetch('domains.txt')
        .then(response => response.text())
        .then(text => {
            let bannedDomains = text.split(/\r?\n/).map(domain => domain.trim());
            let emailParts = email.split("@");

            if (emailParts.length !== 2) {
                alert("Некорректный email!");
                return false;
            }

            let domain = emailParts[1];

            if (bannedDomains.includes(domain)) {
                alert("Этот домен запрещён! Используйте другой email.");
                return false;
            }

            return true;
        })
}

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

async function loadTrainers() {
    try {
        const response = await fetch('form.php');
        const trainers = await response.json();
        const container = document.getElementById('trainer-items');

        if (trainers.length === 0) {
            container.innerHTML = "<p>Пока нет добавленных тренеров</p>";
            return;
        }

        container.innerHTML = '';
        trainers.forEach(t => {
            const div = document.createElement('div');
            div.className = 'trainer';
            div.innerHTML = `
                <h3>${t.name} (${t.age})</h3>
                <p>Пол: ${t.gender === 'male' ? 'Мужской' : 'Женский'}</p>
                <p>Телефон: ${t.phone}</p>
                <p>Email: ${t.email}</p>
                <p>Зал: ${getGymName(t.gym)}</p>
            `;
            container.appendChild(div);
        });
    } catch (err) {
        document.getElementById('trainer-items').innerHTML = "<p>Ошибка загрузки данных</p>";
        console.error(err);
    }
}

function getGymName(id) {
    switch (id) {
        case '1': return 'Липецк, Московская 30';
        case '2': return 'Липецк, Московская 31';
        case '3': return 'Липецк, Московская 32';
        default: return 'Неизвестно';
    }
}


window.addEventListener('DOMContentLoaded', loadTrainers);