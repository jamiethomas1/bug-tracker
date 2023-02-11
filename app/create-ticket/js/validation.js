const createTicket = new JustValidate("#create-ticket");

createTicket
    .addField("#name", [
        {
            rule: "required"
        }
    ])
    .addField("#body", [
        {
            rule: "required"
        }
    ])
    .onSuccess(event => {
        document.getElementById("create-ticket").submit();
    });