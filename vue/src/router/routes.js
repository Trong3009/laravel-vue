const routes = [
  {
    path: "/",
    name: "MainLayout",
    meta: {
      requiresAuth: true,
    },
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "/myvote",
        name: "MyVote",
        component: () => import("src/layouts/MyVote.vue"),
        children: [
          {
            path: "list",
            name: "VotePauseList",
            meta: {
              breadCrumbs: [{ text: "Danh sách phiếu nghỉ" }],
            },
            component: () =>
              import("src/modules/hrm/myvote/VotePauseList.vue"),
          },
          {
            path: "late",
            name: "VoteLateList",
            meta: {
              breadCrumbs: [{ text: "Danh sách phiếu muộn" }],
            },
            component: () =>
              import("src/modules/hrm/myvote/VoteLateList.vue"),
          },
          {
            path: "ot",
            name: "VoteOverTime",
            meta: {
              breadCrumbs: [{ text: "Danh sách phiếu thêm giờ" }],
            },
            component: () =>
              import("src/modules/hrm/myvote/VoteOverTime.vue"),
          },
          {
            path: "onsite",
            name: "VoteOnsite",
            meta: {
              breadCrumbs: [{ text: "Danh sách phiếu làm công ty đối tác" }],
            },
            component: () =>
              import("src/modules/hrm/myvote/VoteOnsite.vue"),
          },
          {
            path: "remote",
            name: "VoteRemote",
            meta: {
              breadCrumbs: [{ text: "Danh sách phiếu làm xa" }],
            },
            component: () =>
              import("src/modules/hrm/myvote/VoteRemote.vue"),
          },
          {
            path: "additional",
            name: "Additional",
            meta: {
              breadCrumbs: [{ text: "Danh sách phiếu bổ sung công" }],
            },
            component: () =>
              import("src/modules/hrm/myvote/VoteAdditional.vue"),
          },
        ],
      },
      {
        path: "/personnel",
        name: "Personnel",
        component: () => import("src/layouts/Personnel.vue"),
        children: [
          {
            path: "list",
            name: "PersonnelList",
            meta: {
              breadCrumbs: [{ text: "Danh sách nhân sự" }],
            },
            component: () =>
              import("src/modules/hrm/personnel/EmployeeList.vue"),
          },
        ],
      },
      {
        path: "/account",
        name: "Account",
        component: () => import("src/layouts/Account.vue"),
        children: [
          {
            path: "list",
            name: "AccountList",
            meta: {
              breadCrumbs: [{ text: "Danh sách tài khoản" }],
            },
            component: () => import("src/modules/hrm/account/AccountList.vue"),
          },
        ],
      },
      {
        path: "/timekeeping",
        name: "TimeKeeping",
        component: () => import("src/layouts/TimeKeeping.vue"),
        children: [
          {
            path: "list",
            name: "TimeKeepingList",
            meta: {
              breadCrumbs: [{ text: "Quản lý chấm công" }],
            },
            component: () =>
              import("src/modules/hrm/timekeeping/EmployeeTimeKeeping.vue"),
          },
          {
            path: "timesheets",
            name: "TimeSheets",
            meta: {
              breadCrumbs: [{ text: "Tổng hợp công" }],
            },
            component: () =>
              import("src/modules/hrm/timekeeping/AggregateWork.vue"),
          },
          {
            path: "permission",
            name: "PermissionManage",
            meta: {
              breadCrumbs: [{ text: "Quản lý phép" }],
            },
            component: () =>
              import("src/modules/hrm/timekeeping/PermissionManage.vue"),
          },
        ],
      },
      {
        path: "/contract",
        name: "Contract",
        component: () => import("src/layouts/Contract.vue"),
        children: [
          {
            path: "list",
            name: "ContractList",
            meta: {
              breadCrumbs: [{ text: "Quản lý hợp đồng" }],
            },
            component: () =>
              import("src/modules/hrm/Contract/ContractList.vue"),
          },
        ],
      },
      {
        path: "/salary",
        name: "Salary",
        component: () => import("src/layouts/Salary.vue"),
        children: [
          {
            path: "componentsalary",
            name: "ComponentSalary",
            meta: {
              breadCrumbs: [{ text: "Thành phần lương" }],
            },
            component: () =>
              import("src/modules/hrm/salary/ComponentSalary.vue"),
          },
          {
            path: "salarysheet",
            name: "SalarySheet",
            meta: {
              breadCrumbs: [{ text: "Mẫu bảng lương" }],
            },
            component: () =>
              import("src/modules/hrm/salary/SalarySheetTemplate.vue"),
          },
          {
            path: "employeepayroll",
            name: "EmployeePayroll",
            meta: {
              breadCrumbs: [{ text: "Tính lương" }],
            },
            component: () =>
              import("src/modules/hrm/salary/EmployeePayroll.vue"),
          },
        ],
      },
      {
        path: "/role",
        name: "Role",
        component: () => import("src/layouts/Role.vue"),
        children: [
          {
            path: "list",
            name: "RoleList",
            meta: {
              breadCrumbs: [{ text: "Danh sách vai trò" }],
            },
            component: () =>
              import("src/modules/hrm/role/RoleList.vue"),
          },
          {
            path: "create/:roleId?",
            name: "CreateRole",
            meta: {
              breadCrumbs: [{ text: "Tạo mới vai trò" }],
            },
            component: () =>
              import("src/modules/hrm/role/CreateRole.vue"),
          },
        ],
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
  {
    path: "/Mail",
    component: () => import("layouts/Mail.vue"),
  },
  {
    path: "/Maintenance",
    component: () => import("pages/Maintenance.vue"),
  },
  {
    path: "/Pricing",
    component: () => import("pages/Pricing.vue"),
  },
  {
    path: "/login",
    component: () => import("pages/Login.vue"),
  },
  {
    path: "/Lock",
    component: () => import("pages/LockScreen.vue"),
  },
  {
    path: "/Lock-2",
    component: () => import("pages/LockScreen-2.vue"),
  },
];

export default routes;
