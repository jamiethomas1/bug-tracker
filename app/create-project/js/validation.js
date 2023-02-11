const createProject = new JustValidate("#create-project");

createProject
    .addField("#name", [
        {
            rule: "required"
        }
    ])
    .onSuccess(event => {
        document.getElementById("create-project").submit();
    });