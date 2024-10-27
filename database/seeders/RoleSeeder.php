<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Membuat permissions untuk Data guru (TeacherController)
        Permission::create(['name' => 'view_teacher']);
        Permission::create(['name' => 'create_teacher']);
        Permission::create(['name' => 'edit_teacher']);
        Permission::create(['name' => 'delete_teacher']);
        Permission::create(['name' => 'import_teacher']);

         // Membuat permissions untuk Data siswa (Student Controller)
         Permission::create(['name' => 'view_student']);
         Permission::create(['name' => 'create_student']);
         Permission::create(['name' => 'edit_student']);
         Permission::create(['name' => 'delete_student']);
         Permission::create(['name' => 'import_student']);

           //Membuat permissions akun guru
        Permission::create(['name' => 'view_account_teacher']);
        Permission::create(['name' => 'create_account_teacher']);
        Permission::create(['name' => 'edit_account_teacher']);
        Permission::create(['name' => 'delete_account_teacher']);

        //Membuat permissions akun siswa
        Permission::create(['name' => 'view_account_student']);
        Permission::create(['name' => 'create_account_student']);
        Permission::create(['name' => 'edit_account_student']);
        Permission::create(['name' => 'delete_account_student']);

        // Membuat permissions untuk Data Mapel (SubjectController)
        Permission::create(['name' => 'view_subject']);
        Permission::create(['name' => 'create_subject']);
        Permission::create(['name' => 'edit_subject']);
        Permission::create(['name' => 'delete_subject']);

        // Membuat permissions untuk Data Semester (SemesterYearController)
        Permission::create(['name' => 'view_semester_year']);
        Permission::create(['name' => 'create_semester_year']);
        Permission::create(['name' => 'edit_semester_year']);
        Permission::create(['name' => 'delete_semester_year']);

        // Membuat permissions untuk Data Kelas (StudentClassController)
        Permission::create(['name' => 'view_class']);
        Permission::create(['name' => 'create_class']);
        Permission::create(['name' => 'edit_class']);
        Permission::create(['name' => 'delete_class']);

         // Membuat permissions untuk rute-rute Guru Mapel (ClassSubjectController)
         Permission::create(['name' => 'view_class_subjects']);
         Permission::create(['name' => 'view_class_subject']);
         Permission::create(['name' => 'create_class_subject']);
         Permission::create(['name' => 'edit_class_subject']);
         Permission::create(['name' => 'delete_class_subject']);

         // Membuat permissions untuk rute-rute Guru Mapel (KnowledgeScoreController)
         Permission::create(['name' => 'view_knowledge_scores']);
         Permission::create(['name' => 'create_knowledge_score']);
         Permission::create(['name' => 'edit_knowledge_score']);
         Permission::create(['name' => 'delete_knowledge_score']);

         // Membuat permissions untuk rute-rute Guru Mapel (AttitudeScoreController)
         Permission::create(['name' => 'view_attitude_scores']);
         Permission::create(['name' => 'create_attitude_score']);
         Permission::create(['name' => 'edit_attitude_score']);
         Permission::create(['name' => 'delete_attitude_score']);

         // Membuat permissions untuk rute-rute Guru Mapel (SkillScoreController)
         Permission::create(['name' => 'view_skill_scores']);
         Permission::create(['name' => 'create_skill_score']);
         Permission::create(['name' => 'edit_skill_score']);
         Permission::create(['name' => 'delete_skill_score']);

         // Membuat permissions untuk rute-rute Guru Kelas (GradeController)
         Permission::create(['name' => 'view_grades']);
         Permission::create(['name' => 'view_grade_detail']);
         Permission::create(['name' => 'edit_knowledge_score_grade']);
         Permission::create(['name' => 'update_knowledge_score_grade']);
         Permission::create(['name' => 'edit_attitude_score_grade']);
         Permission::create(['name' => 'update_attitude_score_grade']);
         Permission::create(['name' => 'edit_skill_score_grade']);
         Permission::create(['name' => 'update_skill_score_grade']);

          // Membuat permissions untuk Guru Mapel (AchievementController)
        Permission::create(['name' => 'view_achievement']);
        Permission::create(['name' => 'create_achievement']);
        Permission::create(['name' => 'edit_achievement']);
        Permission::create(['name' => 'delete_achievement']);

        // Membuat permissions untuk Guru Mapel (ExtracurricularController)
        Permission::create(['name' => 'view_extracurricular']);
        Permission::create(['name' => 'create_extracurricular']);
        Permission::create(['name' => 'edit_extracurricular']);
        Permission::create(['name' => 'delete_extracurricular']);

        // Membuat permissions untuk Guru Mapel (HealthController)
        Permission::create(['name' => 'view_health']);
        Permission::create(['name' => 'create_health']);
        Permission::create(['name' => 'edit_health']);
        Permission::create(['name' => 'delete_health']);

        // Membuat permissions untuk Guru Mapel (HeightWeightController)
        Permission::create(['name' => 'view_height_weight']);
        Permission::create(['name' => 'edit_height_weight']);

        //view_attendance
        Permission::create(['name' => 'view_attendance']);
        Permission::create(['name' => 'update_attendance']);

        // Membuat permissions untuk Guru Mapel (RaporController)
        Permission::create(['name' => 'view_rapors']);
        Permission::create(['name' => 'edit_rapor']);
        Permission::create(['name' => 'edit_suggestion']);
        Permission::create(['name' => 'edit_aspect']);
        Permission::create(['name' => 'validate_rapors']);

         // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kepala_sekolah']);
        Role::create(['name' => 'guru_mapel']);
        Role::create(['name' => 'guru_kelas']);
        Role::create(['name' => 'siswa']);

        // Memberikan permissions kepada role 'admin'
        $adminRole = Role::findByName('admin');

        $adminRole->givePermissionTo([
            'view_account_teacher','create_account_teacher','edit_account_teacher','delete_account_teacher','view_account_student','create_account_student','edit_account_student','delete_account_student','view_student','create_student','edit_student','delete_student','import_student',
            'view_teacher','create_teacher','edit_teacher','delete_teacher','import_teacher',
            'view_subject', 'create_subject', 'edit_subject', 'delete_subject',
            'view_semester_year', 'create_semester_year', 'edit_semester_year', 'delete_semester_year',
            'view_class', 'create_class', 'edit_class', 'delete_class'
        ]);

          // Memberikan permissions kepada role 'guru_kelas'
          $guruMapelRole = Role::findByName('guru_kelas');
          $guruMapelRole->givePermissionTo([
             'view_student','create_student','edit_student','delete_student','import_student',
              'view_class_subjects', 'view_class_subject', 'create_class_subject', 'edit_class_subject', 'delete_class_subject',
              'view_knowledge_scores', 'create_knowledge_score', 'edit_knowledge_score', 'delete_knowledge_score',
              'view_attitude_scores', 'create_attitude_score', 'edit_attitude_score', 'delete_attitude_score',
              'view_skill_scores', 'create_skill_score', 'edit_skill_score', 'delete_skill_score',
              'view_achievement',
              'view_extracurricular',
              'view_health',
              'view_height_weight',
              'view_attendance','update_attendance',
              'view_rapors',
          ]);

          // Memberikan permissions kepada role 'guru_mapel
          $guruKelasRole = Role::findByName('guru_mapel');
          $guruKelasRole->givePermissionTo([
              'view_student','create_student','edit_student','delete_student','import_student',
              'view_grades', 'view_grade_detail',
              'view_height_weight', 'edit_height_weight',
              'view_health','create_health','edit_health','delete_health',
              'view_extracurricular','create_extracurricular','edit_extracurricular','delete_extracurricular',
              'view_achievement','create_achievement','edit_achievement','delete_achievement',
              'view_knowledge_scores','edit_knowledge_score_grade', 'update_knowledge_score_grade',
              'view_attitude_scores','edit_attitude_score_grade', 'update_attitude_score_grade',
              'view_skill_scores','edit_skill_score_grade', 'update_skill_score_grade',
              'view_attendance','update_attendance',
              'view_rapors', 'edit_rapor', 'edit_suggestion', 'edit_aspect',
          ]);

           // Memberikan permissions kepada role 'kepala_sekolah'
        $kepalaSekolahRole = Role::findByName('kepala_sekolah');
        $kepalaSekolahRole->givePermissionTo([
            'view_student','create_student','edit_student','delete_student','import_student',
            'view_teacher','view_rapors', 'validate_rapors',
            'view_knowledge_scores','view_attitude_scores','view_skill_scores','view_achievement', 'view_health','view_height_weight','view_extracurricular',
            'view_attendance',
        ]);

        // Memberikan permissions kepada role 'siswa'
        $siswaRole = Role::findByName('siswa');
        $siswaRole->givePermissionTo([
           'view_attendance','view_knowledge_scores','view_attitude_scores','view_skill_scores','view_achievement', 'view_health','view_height_weight','view_extracurricular', 'view_rapors',
        ]);
    }

}
