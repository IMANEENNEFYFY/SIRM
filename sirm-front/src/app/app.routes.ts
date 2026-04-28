import { Routes } from '@angular/router';
import { MainLayoutComponent } from './layout/main-layout/main-layout.component';
import { DashboardComponent } from './features/dashboard/dashboard.component';
import { PatientsComponent } from './features/patients/patients.component';
import { ExamensComponent } from './features/examens/examens.component';
import { ResultatsComponent } from './features/resultats/resultats.component';
import { LoginComponent } from './features/login/login.component';
import { ViewerComponent } from './features/viewer/viewer.component';
import { authGuard } from './core/guards/auth.guard';

export const routes: Routes = [
	{
		path: 'login',
		component: LoginComponent
	},
	{
		path: '',
		component: MainLayoutComponent,
		canActivate: [authGuard],
		children: [
			{ path: '', redirectTo: 'tableau-de-bord', pathMatch: 'full' },
			{ path: 'tableau-de-bord', component: DashboardComponent },
			{ path: 'patients', component: PatientsComponent },
			{ path: 'examens', component: ExamensComponent },
			{ path: 'resultats', component: ResultatsComponent },
			{ path: 'viewer', component: ViewerComponent }
		]
	},
	{ path: '**', redirectTo: 'login' }
];
