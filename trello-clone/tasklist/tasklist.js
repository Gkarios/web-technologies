const newTaskListBtn = document.querySelector(".addBtn");
const container = document.querySelector("#container");
newTaskListBtn.addEventListener("click", getListName);
let totalLists = 0;

function getListName() {
  const taskList = document.createElement("div");
  const listName = document.createElement("input");
  const btnWrapper = document.createElement("div");
  const confirmTaskList = document.createElement("button");
  const cancelTaskList = document.createElement("button");
  taskList.classList.add("tasklist");
  confirmTaskList.textContent = "Add title";
  cancelTaskList.textContent = "Cancel";
  container.appendChild(taskList);
  taskList.appendChild(listName);
  taskList.appendChild(btnWrapper);
  btnWrapper.appendChild(confirmTaskList);
  btnWrapper.appendChild(cancelTaskList);
  listName.focus();

  listName.addEventListener("blur", handleBlur);

  confirmTaskList.addEventListener("mousedown", handleConfirm);

  cancelTaskList.addEventListener("mousedown", handleCancel);

  function handleBlur() {
    if (this.value == "") {
      taskList.remove();
    } else {
      createTaskList();
      btnWrapper.remove();
    }
    listName.removeEventListener("blur", handleBlur);
  }

  function handleConfirm(e) {
    e.preventDefault();
    if (listName.value !== "") {
      listName.blur();
    }
  }

  function handleCancel(ev) {
    ev.preventDefault();
    listName.removeEventListener("blur", handleBlur);
    taskList.remove();
    console.log(document.querySelector(".tasklist"));
  }
}

function createTaskList() {
  totalLists++;
  const taskList = document.querySelectorAll(".tasklist")[totalLists - 1];
  const newTaskBtn = document.createElement("button");
  const delList = document.createElement("button");
  delList.textContent = "Delete list";
  newTaskBtn.textContent = "Add new task";
  taskList.appendChild(newTaskBtn);
  taskList.appendChild(delList);

  delList.addEventListener("click", () => {
    totalLists--;
    taskList.remove();
  });

  newTaskBtn.addEventListener("click", () => {
    const task = document.createElement("div");
    const btnWrapper = document.createElement("div");
    const nameWrapper = document.createElement("div");
    const viewTaskOptions = document.createElement("img");
    const confirmTask = document.createElement("button");
    const cancelTask = document.createElement("button");
    const taskName = document.createElement("input");
    confirmTask.textContent = "Add Task";
    cancelTask.textContent = "Cancel";
    viewTaskOptions.src = "dots.png";

    task.classList.add("task");
    viewTaskOptions.classList.add("viewOptions");
    nameWrapper.appendChild(taskName);
    btnWrapper.appendChild(confirmTask);
    btnWrapper.appendChild(cancelTask);
    task.appendChild(btnWrapper);
    task.appendChild(nameWrapper);
    taskList.appendChild(task);
    taskName.focus();

    taskName.addEventListener("blur", handleBlur);

    confirmTask.addEventListener("mousedown", handleConfirm);

    cancelTask.addEventListener("mousedown", handleCancel);

    function handleBlur() {
      if (this.value == "") {
        task.remove();
      } else {
        createTask();
        btnWrapper.remove();
      }
      taskName.removeEventListener("blur", handleBlur);
    }

    function handleConfirm(e) {
      e.preventDefault();
      if (taskName.value !== "") {
        taskName.blur();
      }
    }

    function handleCancel(e) {
      e.preventDefault();
      taskName.removeEventListener("blur", handleBlur);
      task.remove();
    }

    function createTask() {
      nameWrapper.appendChild(viewTaskOptions);
      nameWrapper.classList.add("nameWrap");
      const delTask = document.createElement("button");
      const taskOptHeader = document.createElement("h4");
      taskOptHeader.textContent = "Task Actions";
      const d = new Date();
      const p = document.createElement("p");
      p.textContent = `${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}
      ${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
      task.appendChild(p);
      delTask.textContent = "Delete Task";

      const taskOptions = document.createElement("div");
      task.appendChild(taskOptions);
      taskOptions.appendChild(taskOptHeader);
      taskOptions.appendChild(delTask);
      taskOptions.classList.add("taskOptions");

      delTask.addEventListener("click", () => {
        task.remove();
      });

      viewTaskOptions.addEventListener("click", (e) => {
        e.stopPropagation();
        const allMenus = document.querySelectorAll(".taskOptions");
        allMenus.forEach((menu) => {
          if (menu.style.display != "none" && menu != taskOptions) {
            menu.style.display = "none";
          }
        });
        if (taskOptions.style.display != "block") {
          taskOptions.style.display = "block";
        } else {
          taskOptions.style.display = "none";
        }
      });

      document.addEventListener("click", (e) => {
        if (
          e.target != taskOptions &&
          !taskOptions.contains(e.target) &&
          taskOptions.style.display != "none"
        ) {
          taskOptions.style.display = "none";
        }
      });
    }
  });
}
