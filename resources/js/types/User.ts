import type { Article } from './Article';
import type { Comment } from './Comment';
import type { Role } from './Role';

export interface User {
  id: number;
  name: string;
  email: string;
  password: string;
  roles: Role[];
  articles?: Article[];
  comments?: Comment[];
}
