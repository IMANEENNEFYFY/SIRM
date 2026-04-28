import { Injectable } from '@angular/core';
import { Observable, map, tap } from 'rxjs';
import { LoginPayload } from '../models/sirm.models';
import { SirmApiService } from './sirm-api.service';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly tokenKey = 'sirm_jwt_token';

  constructor(private readonly apiService: SirmApiService) {}

  login(payload: LoginPayload): Observable<string> {
    return this.apiService.login(payload).pipe(
      tap((response) => localStorage.setItem(this.tokenKey, response.token)),
      map((response) => response.token)
    );
  }

  logout(): void {
    localStorage.removeItem(this.tokenKey);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  isAuthenticated(): boolean {
    return !!this.getToken();
  }
}
