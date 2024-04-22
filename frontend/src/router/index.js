import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
const routes = [
  {
    path: "/",
    component: () => import("../pages/layout/App.vue"),
    children: [
      {
        path: "/",
        redirect: { name: "home" },
      },
      {
        path: "dashboard",
        name: "home",
        component: () => import("../pages/layout/Dashboard.vue"),
      },
      {
        path: "/subject",
        name: "subject.index",
        component: () => import("../pages/subject/SubjectIndex.vue"),
      },
      {
        path: "/user",
        name: "user.index",
        component: () => import("../pages/user/UserIndex.vue"),
      },
      {
        path: "/user/:user",
        name: "user.show",
        component: () => import("../pages/user/UserShow.vue"),
      },
      {
        path: "/studyyear",
        name: "studyyear.index",
        component: () => import("../pages/study-year/StudyYearIndex.vue"),
      },
      {
        path: "/study_year/:study_year",
        name: "studyyear.show",
        component: () => import("../pages/study-year/StudyYearShow.vue"),
      },
      {
        path: "/role",
        name: "role.index",
        component: () => import("../pages/role/RoleIndex.vue"),
      },
      {
        path: "/role/create",
        name: "role.create",
        component: () => import("../pages/role/RoleCreate.vue"),
      },
      {
        path: "/role/:role/edit",
        name: "role.edit",
        component: () => import("../pages/role/RoleEdit.vue"),
      },
      {
        path: "/permission",
        name: "permission.index",
        component: () => import("../pages/permission-list/PermissionIndex.vue"),
      },
      {
        path: "/student",
        name: "student.index",
        component: () => import("../pages/student/StudentIndex.vue"),
      },
      {
        path: "/student/create",
        name: "student.create",
        component: () => import("../pages/student/StudentCreate.vue"),
      },
      {
        path: "/student/:student/edit",
        name: "student.edit",
        component: () => import("../pages/student/StudentEdit.vue"),
      },
      {
        path: "/student/:student/show",
        name: "student.show",
        component: () => import("../pages/student/StudentShow.vue"),
      },
      {
        path: "/room",
        name: "room.index",
        component: () => import("../pages/room/RoomIndex.vue"),
      },
      {
        path: "/grade",
        name: "grade.index",
        component: () => import("../pages/grade/GradeIndex.vue"),
      },
      {
        path: "/teacher",
        name: "teacher.index",
        component: () => import("../pages/teacher/TeacherIndex.vue"),
      },
      {
        path: "/studyclass",
        name: "studyclass.index",
        component: () => import("../pages/study-class/StudyClassIndex.vue"),
      },
      {
        path: "/studyclass/create",
        name: "studyclass.create",
        component: () => import("../pages/study-class/StudyClassCreate.vue"),
      },
      {
        path: "/studyclass/:studyclass/edit",
        name: "studyclass.edit",
        component: () => import("../pages/study-class/StudyClassEdit.vue"),
      },
      {
        path: "/studyclass/:studyclass/show",
        name: "studyclass.show",
        component: () => import("../pages/study-class/StudyClassShow.vue"),
      },
      {
        path: "/studyclass/:studyclass/attendance/save",
        name: "attendance.save",
        component: () => import("../pages/study-class/attendance/AttendanceSave.vue"),
      },
      {
        path: "/studyclass/:studyclass/attendance/list",
        name: "attendance.list",
        component: () => import("../pages/study-class/attendance/AttendanceList.vue"),
      },
      {
        path: "/studyclass/:studyclass/attendance/report",
        name: "attendance.report",
        component: () => import("../pages/study-class/attendance/AttendanceReport.vue"),
      },
      {
        path: "/studyclass/:studyclass/score/save",
        name: "score.save",
        component: () => import("../pages/study-class/score/ScoreSave.vue"),
      },
      {
        path: "/studyclass/:studyclass/score/list",
        name: "score.list",
        component: () => import("../pages/study-class/score/ScoreList.vue"),
      },
      {
        path: "/studyclass/:studyclass/score/ranking",
        name: "score.ranking",
        component: () => import("../pages/study-class/score/ScoreRanking.vue"),
      },
    ],
  },
  {
    path: "/",
    component: () => import("../pages/layout/Guest.vue"),
    children: [
      {
        path: "/login",
        name: "login",
        component: () => import("../pages/Auth/Login.vue"),
      },
    ],
  },
  {
    path: "/no-permission",
    name: "no-permission",
    component: () => import("../pages/errors/PageNoPermission.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: "active",
  linkExactActiveClass: "exact-active",
});

router.beforeEach((to, from, next) => {
  const guestRoute = ["login"];
  if (store.state.auth.authenticated) {
    if (guestRoute.includes(to.name)) {
      next({ name: "home" });
    } else {
      if (!to.meta.permission) {
        next();
      } else {
        let verify = false;
        store.state.auth.user?.permissions?.forEach((e) => {
          if (e.includes(to.meta.permission) == true) {
            verify = true;
          }
        });
        if (verify) {
          next();
        } else {
          next({ name: "no-permission" });
        }
      }
    }
  } else {
    if (guestRoute.includes(to.name)) next();
    else next({ name: "login" });
  }
});
export default router;
