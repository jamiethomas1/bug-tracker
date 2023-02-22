const ticketReply = new JustValidate("#ticket-reply");

ticketReply
    .addField("#body", [
        {
            rule: "required"
        }
    ])
    .onSuccess(event => {
        document.getElementById("ticket-reply").submit();
    });