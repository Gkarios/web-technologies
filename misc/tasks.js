const newTaskListBtn = document.querySelector(".addBtn");
const container = document.querySelector("#container");
const modalContainer = document.querySelector("#modal-container");
const modalContent = document.querySelector("#modal-content");
const modalDelBtn = document.querySelector(".deleteBtn");
const modalCancelBtn = document.querySelector(".cancelBtn");

newTaskListBtn.addEventListener("click", getListName);

function showModal(taskList) {
  let liName = taskList.querySelector("textarea").value;
  let isNameTooLong = liName.length > 60;
  let addEllipsis;
  let fixedListName;
  addEllipsis = isNameTooLong ? "..." : "";
  fixedListName = isNameTooLong ? liName.slice(0, 60) : liName;
  modalContainer.setAttribute("style", "display:flex");
  modalContent.querySelector("p").textContent =
    "Tasklist " +
    '"' +
    fixedListName +
    addEllipsis +
    '"' +
    " will be pernamently deleted.";

  function deleteTaskList() {
    taskList.remove();
    modalContainer.setAttribute("style", "display:none");
    cleanup();
  }

  function cleanup() {
    //modalCancelBtn.removeEventListener("click", cancel);
    modalDelBtn.removeEventListener("click", deleteTaskList);
  }

  function cancel() {
    modalContainer.setAttribute("style", "display:none");
    cleanup();
  }

  modalCancelBtn.addEventListener("click", cancel);
  modalDelBtn.addEventListener("click", deleteTaskList);
}

function fixTextArea(tx) {
  tx.setAttribute(
    "style",
    "height:" + tx.scrollHeight + "px;overflow-y:hidden;"
  );
  tx.addEventListener("input", OnInput, false);
  tx.setAttribute("spellcheck", false);

  function OnInput() {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
  }
}

function handleEnter(e) {
  if (e.key == "Enter") {
    e.target.blur();
  }
}

function getListName() {
  newTaskListBtn.setAttribute("style", "display:none;");
  const taskList = document.createElement("div");
  const listName = document.createElement("textarea");
  const btnWrapper = document.createElement("div");
  const confirmTaskList = document.createElement("button");
  const cancelTaskList = document.createElement("button");
  taskList.classList.add("tasklist");
  btnWrapper.classList.add("btnWrapper");
  confirmTaskList.textContent = "Add title";
  cancelTaskList.textContent = "Cancel";
  container.insertBefore(taskList, newTaskListBtn);
  taskList.appendChild(listName);
  taskList.appendChild(btnWrapper);
  btnWrapper.appendChild(confirmTaskList);
  btnWrapper.appendChild(cancelTaskList);
  listName.focus();

  listName.addEventListener("blur", handleBlur);

  listName.addEventListener("keydown", handleEnter);

  confirmTaskList.addEventListener("mousedown", handleConfirm);

  cancelTaskList.addEventListener("mousedown", handleCancel);

  fixTextArea(listName);

  function handleBlur() {
    if (this.value.trim() == "") {
      taskList.remove();
    } else {
      createTaskList(listName, taskList); //This creates a task list
      btnWrapper.remove();
    }
    newTaskListBtn.setAttribute("style", "display:inline-block;");
    listName.removeEventListener("blur", handleBlur);
  }

  function handleEnter(e) {
    if (e.key == "Enter") {
      if (listName.value.trim() != "") {
        listName.blur();
      }
    }
  }

  function handleConfirm(e) {
    e.preventDefault();
    if (listName.value.trim() !== "") {
      listName.blur();
    }
  }

  function handleCancel(e) {
    e.preventDefault();
    taskList.remove();
    newTaskListBtn.setAttribute("style", "display:inline-block;");
    listName.removeEventListener("blur", handleBlur);
  }
}

function createTaskList(listName, taskList) {
  const newTaskBtn = document.createElement("button");
  const taskListHeader = document.createElement("div");
  const delList = document.createElement("span");
  const collapseList = document.createElement("span");
  const plusSign = document.createElement("span");
  const textInBtn = document.createElement("span");
  const toDoTasks = document.createElement("div");
  const inProgTasks = document.createElement("div");
  const completedTasks = document.createElement("div");
  const unclassifiedTasks = document.createElement("div");

  const toDoHeader = document.createElement("h3");
  const inProgHeader = document.createElement("h3");
  const completedHeader = document.createElement("h3");
  const unclassifiedHeader = document.createElement("h3");
  const toDoTogBtn = document.createElement("span");
  const inProgTogBtn = document.createElement("span");
  const completedTogBtn = document.createElement("span");
  const unclassifiedTogBtn = document.createElement("span");

  delList.textContent = "✖";
  delList.classList.add("delListBtn");
  plusSign.textContent = "+";
  textInBtn.textContent = "Add a new task";
  collapseList.classList.add("fa-solid", "fa-minimize", "collapseList");
  newTaskBtn.classList.add("newTaskBtn");
  taskListHeader.appendChild(listName);
  taskListHeader.appendChild(collapseList);
  taskListHeader.appendChild(delList);
  taskListHeader.classList.add("taskListHeader");
  toDoHeader.textContent = "To Do";
  inProgHeader.textContent = "In Progress";
  completedHeader.textContent = "Completed";
  unclassifiedHeader.textContent = "Unclassified";

  toDoTasks.classList.add("toDoCont", "taskStateCont");
  inProgTasks.classList.add("inProgCont", "taskStateCont");
  completedTasks.classList.add("completedCont", "taskStateCont");
  unclassifiedTasks.classList.add("unclassifiedCont", "taskStateCont");
  toDoTogBtn.classList.add("fa", "fa-angle-down", "toggleBtn");
  inProgTogBtn.classList.add("fa", "fa-angle-down", "toggleBtn");
  completedTogBtn.classList.add("fa", "fa-angle-down", "toggleBtn");
  unclassifiedTogBtn.classList.add("fa", "fa-angle-down", "toggleBtn");

  let previousListName = listName.value;

  newTaskBtn.appendChild(plusSign);
  newTaskBtn.appendChild(textInBtn);
  toDoHeader.appendChild(toDoTogBtn);
  inProgHeader.appendChild(inProgTogBtn);
  completedHeader.appendChild(completedTogBtn);
  unclassifiedHeader.appendChild(unclassifiedTogBtn);
  toDoTasks.appendChild(toDoHeader);
  inProgTasks.appendChild(inProgHeader);
  completedTasks.appendChild(completedHeader);
  unclassifiedTasks.appendChild(unclassifiedHeader);
  taskList.appendChild(taskListHeader);
  taskList.appendChild(newTaskBtn);
  taskList.insertBefore(toDoTasks, newTaskBtn);
  taskList.insertBefore(inProgTasks, newTaskBtn);
  taskList.insertBefore(completedTasks, newTaskBtn);
  taskList.insertBefore(unclassifiedTasks, newTaskBtn);

  listName.addEventListener("keydown", handleEnterList);

  delList.addEventListener("click", function () {
    showModal(taskList);
  });

  collapseList.addEventListener("click", () => {
    let isCollapsed = collapseList.classList.contains("fa-maximize");
    if (isCollapsed) {
      collapseList.classList.remove("fa-maximize");
      collapseList.classList.add("fa-minimize");
    } else {
      collapseList.classList.add("fa-maximize");
      collapseList.classList.remove("fa-minimize");
    }
    const stateContainers = taskList.children;
    for (let i = 1; i < stateContainers.length - 1; i++) {
      if (isCollapsed) {
        if (stateContainers[i].children.length > 1) {
          stateContainers[i].style.display = "flex";
        }
      } else {
        stateContainers[i].style.display = "none";
      }
    }
  });

  listName.addEventListener("change", () => {
    if (listName.value.trim() == "") {
      listName.value = previousListName;
    } else {
      previousListName = listName.value;
    }
  });

  function handleEnterList(e) {
    if (e.key == "Enter") {
      listName.blur();
    }
  }

  function toggleVisibility(e) {
    let correctToggle =
      e.target.tagName == "H3" ? e.target : e.target.parentElement;
    const toggleArrow = correctToggle.children[0];
    const stateContTasks = correctToggle.parentElement.children;
    let isVisible = toggleArrow.classList.contains("fa-angle-up");

    for (let i = 1; i < stateContTasks.length; i++) {
      stateContTasks[i].style.display = isVisible ? "none" : "flex";
    }

    if (isVisible) {
      toggleArrow.classList.add("fa-angle-down");
      toggleArrow.classList.remove("fa-angle-up");
    } else {
      toggleArrow.classList.add("fa-angle-up");
      toggleArrow.classList.remove("fa-angle-down");
    }
  }

  toDoHeader.addEventListener("click", toggleVisibility);
  inProgHeader.addEventListener("click", toggleVisibility);
  completedHeader.addEventListener("click", toggleVisibility);
  unclassifiedHeader.addEventListener("click", toggleVisibility);

  newTaskBtn.addEventListener("click", () => {
    newTaskBtn.setAttribute("style", "display:none;");
    const taskContainer = document.createElement("div");
    const task = document.createElement("div");
    const btnWrapper = document.createElement("div");
    const confirmTask = document.createElement("button");
    const cancelTask = document.createElement("button");
    const taskName = document.createElement("textarea");
    const line = document.createElement("div");
    line.classList.add("line2");
    taskName.classList.add("taskName");
    confirmTask.textContent = "Add Task";
    cancelTask.textContent = "Cancel";
    btnWrapper.classList.add("btnWrapper");
    btnWrapper.appendChild(confirmTask);
    btnWrapper.appendChild(cancelTask);
    task.appendChild(taskName);
    taskContainer.appendChild(task);
    taskContainer.appendChild(btnWrapper);
    taskContainer.classList.add("taskContainer");
    task.classList.add("task");
    taskList.insertBefore(taskContainer, newTaskBtn);
    let isListNotEmpty =
      toDoTasks.style.display == "flex" ||
      inProgTasks.style.display == "flex" ||
      completedTasks.style.display == "flex";
    if (unclassifiedTasks.children.length == 1 && isListNotEmpty) {
      taskList.insertBefore(line, unclassifiedTasks);
    }

    fixTextArea(taskName);

    taskName.focus();

    taskName.addEventListener("blur", handleBlur);

    taskName.addEventListener("keydown", handleEnter);

    confirmTask.addEventListener("mousedown", handleConfirm);

    cancelTask.addEventListener("mousedown", handleCancel);

    function handleBlur() {
      if (this.value.trim() != "") {
        createTask(
          taskList,
          newTaskBtn,
          task,
          taskName,
          toDoTasks,
          inProgTasks,
          completedTasks,
          toDoTogBtn,
          inProgTogBtn,
          completedTogBtn,
          unclassifiedTasks,
          unclassifiedTogBtn
        );
      }
      btnWrapper.remove();
      taskContainer.remove();
      line.remove();
      taskName.removeEventListener("blur", handleBlur);
      newTaskBtn.setAttribute("style", "display:inline-block;");
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
      taskContainer.remove();
      line.remove();
      newTaskBtn.setAttribute("style", "display:inline-block;");
    }
  });
}

function createTask(
  taskList,
  newTaskBtn,
  task,
  taskName,
  toDoTasks,
  inProgTasks,
  completedTasks,
  toDoTogBtn,
  inProgTogBtn,
  doneTogBtn,
  unclassifiedTasks,
  unclassifiedTogBtn
) {
  const viewTaskOptions = document.createElement("span");
  viewTaskOptions.textContent = "⋮";
  viewTaskOptions.classList.add("viewOptions");
  task.appendChild(viewTaskOptions);
  task.classList.add("task");
  const taskOptHeader = document.createElement("h4");
  const taskOptions = document.createElement("div");
  const delTask = document.createElement("button");
  const editState = document.createElement("button");
  const assignToUser = document.createElement("button");
  const editStateWrap = document.createElement("div");
  const toDo = document.createElement("button");
  const inProgress = document.createElement("button");
  const completed = document.createElement("button");
  const closeTaskOpt = document.createElement("span");
  const line = document.createElement("div");
  const line2 = document.createElement("div");
  const stateToggleBtn = document.createElement("span");
  const currentStateSelector = document.createElement("span");
  let currentdate = new Date().getTime();
  let previousTaskName = taskName.value;

  task.setAttribute("data-date", currentdate);
  stateToggleBtn.classList.add("fa", "fa-angle-down", "toggleBtn");
  line.classList.add("line");
  line2.classList.add("line");
  editStateWrap.classList.add("editStateWrap");
  currentStateSelector.classList.add("fa", "fa-check", "currentStateSelector");
  closeTaskOpt.textContent = "    ✖";
  taskOptHeader.textContent = "Task Actions";
  delTask.textContent = "Delete Task";
  editState.textContent = "Change status of task";
  assignToUser.textContent = "Assign task to another user";
  toDo.textContent = "To Do";
  inProgress.textContent = "In Progress";
  completed.textContent = "Completed";

  const searchContainer = document.createElement("div");
  const userInp = document.createElement("textarea");
  searchContainer.classList.add("searchContainer");
  userInp.placeholder = "Search username";
  searchContainer.appendChild(userInp);

  editState.appendChild(stateToggleBtn);
  editStateWrap.appendChild(toDo);
  editStateWrap.appendChild(inProgress);
  editStateWrap.appendChild(completed);
  taskOptHeader.appendChild(closeTaskOpt);
  taskOptions.appendChild(taskOptHeader);
  taskOptions.appendChild(delTask);
  taskOptions.appendChild(line);
  taskOptions.appendChild(editState);
  taskOptions.appendChild(editStateWrap);
  taskOptions.appendChild(line2);
  taskOptions.appendChild(assignToUser);
  taskOptions.appendChild(searchContainer);
  taskOptions.classList.add("taskOptions");
  task.appendChild(taskOptions);
  unclassifiedTasks.appendChild(task);
  unclassifiedTasks.style.display = "flex";
  unclassifiedTogBtn.classList.add("fa-angle-up");
  unclassifiedTogBtn.classList.remove("fa-angle-down");
  const unclTasks = Array.from(unclassifiedTasks.children);
  unclTasks.sort((a, b) => {
    return b.dataset.date - a.dataset.date;
  });
  unclTasks.forEach((task) => {
    if (task.tagName != "H3") {
      task.style.display = "flex";
    }
  });
  let collapseBtn = taskList.querySelector("span");
  let isListEmpty =
    toDoTasks.style.display != "flex" &&
    inProgTasks.style.display != "flex" &&
    completedTasks.style.display != "flex";
  if(isListEmpty && taskList.querySelector(".fa-maximize")!=null){
    collapseBtn.classList.replace("fa-maximize", "fa-minimize");
  }
  unclassifiedTasks.append(...unclTasks);

  task.addEventListener("mouseover", handleMouseover);

  task.addEventListener("mouseout", handleMouseout);

  taskName.addEventListener("keydown", handleEnter);

  let currentColor = "gray";

  taskName.addEventListener("change", () => {
    if (taskName.value.trim() == "") {
      taskName.value = previousTaskName;
    } else {
      previousTaskName = taskName.value;
    }
  });

  function setState(e, stateCont, togBtn, color) {
    task.style.borderColor = color;
    currentColor = color;
    if (task.parentElement.children.length == 2) {
      task.parentElement.style.display = "none";
    }
    stateCont.appendChild(task);
    stateCont.style.display = "flex";
    togBtn.classList.add("fa-angle-up");
    togBtn.classList.remove("fa-angle-down");
    e.target.appendChild(currentStateSelector);
    hideTaskOptions(false);

    const stateContTasks = Array.from(stateCont.children);
    stateContTasks.sort((a, b) => {
      return b.dataset.date - a.dataset.date;
    });
    stateCont.append(...stateContTasks);
  }

  taskName.addEventListener("focus", () => {
    viewTaskOptions.style.visibility = "hidden";
    task.removeEventListener("mouseover", handleMouseover);
    task.style.borderColor = "blue";
  });

  taskName.addEventListener("blur", (e) => {
    task.style.borderColor = currentColor;
    task.addEventListener("mouseover", handleMouseover);
  });

  function handleEnter(e) {
    if (e.key == "Enter") {
      e.target.blur();
    }
    viewTaskOptions.style.visibility = "visible";
  }

  function handleMouseover() {
    viewTaskOptions.style.visibility = "visible";
    const allMenus = document.querySelectorAll(".taskOptions");
    allMenus.forEach((menu) => {
      if (
        (menu.style.display != "none" && menu != taskOptions) ||
        (menu.style.display == "none" && menu == taskOptions)
      ) {
        task.addEventListener("mouseout", handleMouseout);
      }
    });
  }

  function handleMouseout() {
    viewTaskOptions.style.visibility = "hidden";
  }

  function hideTaskOptions(param) {
    taskOptions.style.display = "none";
    editStateWrap.style.display = "none";
    searchContainer.style.display = "none";
    stateToggleBtn.classList.add("fa-angle-down");
    stateToggleBtn.classList.remove("fa-angle-up");
    task.addEventListener("mouseout", handleMouseout);
    if (param) {
      viewTaskOptions.style.visibility = "hidden";
    }
  }

  viewTaskOptions.addEventListener("click", (e) => {
    e.stopPropagation();
    const allMenus = document.querySelectorAll(".taskOptions");
    allMenus.forEach((menu) => {
      if (menu.style.display != "none" && menu != taskOptions) {
        menu.style.display = "none";
      }
    });

    const allInfoDots = document.querySelectorAll(".viewOptions");
    allInfoDots.forEach((infoDots) => {
      if (infoDots.style.visibility != "hidden" && infoDots != e.target) {
        infoDots.style.visibility = "hidden";
      }
    });

    if (taskOptions.style.display != "block") {
      taskOptions.style.display = "block";
      taskOptions.classList.add("inViewPort");
      taskOptions.classList.remove("outOfViewPort");
      let bounding = taskOptions.getBoundingClientRect();
      if (bounding.bottom >= window.innerHeight) {
        console.log("out of viewport");
        taskOptions.classList.add("outOfViewPort");
        taskOptions.classList.remove("inViewPort");
      }
      task.removeEventListener("mouseout", handleMouseout);
    } else {
      hideTaskOptions(false);
    }
  });

  document.addEventListener("click", (e) => {
    if (
      e.target != taskOptions &&
      !taskOptions.contains(e.target) &&
      taskOptions.style.display != "none"
    ) {
      hideTaskOptions(true);
    }
  });

  closeTaskOpt.addEventListener("click", () => {
    hideTaskOptions(true);
  });

  delTask.addEventListener("click", () => {
    if (task.parentElement.children.length == 2) {
      task.parentElement.style.display = "none";
    }
    task.remove();
  });

  editState.addEventListener("click", () => {
    let isVisible = editStateWrap.style.display == "flex";
    editStateWrap.style.display = isVisible ? "none" : "flex";
    if (isVisible) {
      stateToggleBtn.classList.add("fa-angle-down");
      stateToggleBtn.classList.remove("fa-angle-up");
    } else {
      stateToggleBtn.classList.add("fa-angle-up");
      stateToggleBtn.classList.remove("fa-angle-down");
    }
  });

  toDo.addEventListener("click", (e) => {
    setState(e, toDoTasks, toDoTogBtn, "red");
  });

  inProgress.addEventListener("click", (e) => {
    setState(e, inProgTasks, inProgTogBtn, "orange");
  });

  completed.addEventListener("click", (e) => {
    setState(e, completedTasks, doneTogBtn, "green");
  });

  assignToUser.addEventListener("click", () => {
    let isVisible = searchContainer.style.display == "block";
    searchContainer.style.display = isVisible ? "none" : "block";
    fixTextArea(userInp);
    userInp.focus();
  });

  userInp.addEventListener("keydown", handleEnter);
}
