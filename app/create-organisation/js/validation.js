const createOrganisation = new JustValidate("#create-organisation");

createOrganisation
    .addField("#name", [
        {
            rule: "required"
        }
    ])
    .onSuccess(event => {
        document.getElementById("create-organisation").submit();
    });