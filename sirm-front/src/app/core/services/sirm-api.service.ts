import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import {
  DicomNonReconcilie,
  DicomSource,
  Examen,
  ExamenCreatePayload,
  ExamenUpdatePayload,
  LoginPayload,
  LoginResponse,
  Machine,
  Patient,
  PatientCreatePayload,
  PatientUpdatePayload,
  ResumeStats,
  StatutExamen
} from '../models/sirm.models';

@Injectable({ providedIn: 'root' })
export class SirmApiService {
  private readonly baseUrl = '/api';

  constructor(private readonly http: HttpClient) {}

  login(payload: LoginPayload): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${this.baseUrl}/auth`, payload);
  }

  getPatients(query = ''): Observable<Patient[]> {
    let params = new HttpParams();
    if (query.trim()) {
      params = params.set('q', query.trim());
    }

    return this.http.get<Patient[]>(`${this.baseUrl}/patients`, { params });
  }

  createPatient(payload: PatientCreatePayload): Observable<Patient> {
    return this.http.post<Patient>(`${this.baseUrl}/patients`, payload);
  }

  updatePatient(id: number, payload: PatientUpdatePayload): Observable<Patient> {
    return this.http.put<Patient>(`${this.baseUrl}/patients/${id}`, payload);
  }

  getMachines(): Observable<Machine[]> {
    return this.http.get<Machine[]>(`${this.baseUrl}/machines`);
  }

  getExamens(statut?: StatutExamen): Observable<Examen[]> {
    let params = new HttpParams();
    if (statut) {
      params = params.set('statut', statut);
    }

    return this.http.get<Examen[]>(`${this.baseUrl}/examens`, { params });
  }

  getExamenById(id: number): Observable<Examen> {
    return this.http.get<Examen>(`${this.baseUrl}/examens/${id}`);
  }

  createExamen(payload: ExamenCreatePayload): Observable<Examen> {
    return this.http.post<Examen>(`${this.baseUrl}/examens`, payload);
  }

  updateExamen(id: number, payload: ExamenUpdatePayload): Observable<Examen> {
    return this.http.put<Examen>(`${this.baseUrl}/examens/${id}`, payload);
  }

  getResumeStats(): Observable<ResumeStats> {
    return this.http.get<ResumeStats>(`${this.baseUrl}/examens/stats/resume`);
  }

  changerStatutExamen(id: number, statut: StatutExamen): Observable<Examen> {
    return this.http.patch<Examen>(`${this.baseUrl}/examens/${id}/statut`, { statut });
  }

  genererWorklist(id: number): Observable<{ message: string; examen: Examen }> {
    return this.http.post<{ message: string; examen: Examen }>(`${this.baseUrl}/examens/${id}/worklist`, {});
  }

  recreerExamen(id: number): Observable<Examen> {
    return this.http.post<Examen>(`${this.baseUrl}/examens/${id}/recreer`, {});
  }

  getDicomSource(examenId: number): Observable<DicomSource> {
    return this.http.get<DicomSource>(`${this.baseUrl}/examens/${examenId}/dicom-source`);
  }

  getNonReconcilies(): Observable<DicomNonReconcilie[]> {
    return this.http.get<DicomNonReconcilie[]>(`${this.baseUrl}/dicom/non-reconcilies`);
  }

  validerResultatNonReconcilie(id: number, examenId: number): Observable<{ message: string; examen: Examen }> {
    return this.http.post<{ message: string; examen: Examen }>(
      `${this.baseUrl}/dicom/non-reconcilies/${id}/valider`,
      { examenId }
    );
  }
}
