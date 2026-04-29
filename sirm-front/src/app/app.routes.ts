import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';

export const routes: Routes = [
	{
		path: 'login',
		loadComponent: () => import('./features/login/login.component').then((m) => m.LoginComponent)
	},
	{
		path: '',
		loadComponent: () =>
			import('./layout/main-layout/main-layout.component').then((m) => m.MainLayoutComponent),
		canActivate: [authGuard],
		children: [
			{ path: '', redirectTo: 'tableau-de-bord', pathMatch: 'full' },
			{
				path: 'tableau-de-bord',
				loadComponent: () =>
					import('./features/dashboard/dashboard.component').then((m) => m.DashboardComponent)
			},
			{
				path: 'patients',
				loadComponent: () =>
					import('./features/patients/patients.component').then((m) => m.PatientsComponent)
			},
			{
				path: 'examens',
				loadComponent: () =>
					import('./features/examens/examens.component').then((m) => m.ExamensComponent)
			},
			{
				path: 'machines',
				loadComponent: () =>
					import('./features/machines/machines.component').then((m) => m.MachinesComponent)
			},
			{
				path: 'resultats',
				loadComponent: () =>
					import('./features/resultats/resultats.component').then((m) => m.ResultatsComponent)
			},
			{
				path: 'viewer',
				loadComponent: () =>
					import('./features/viewer/viewer.component').then((m) => m.ViewerComponent)
			}
		]
	},
	{ path: '**', redirectTo: 'login' }
];
