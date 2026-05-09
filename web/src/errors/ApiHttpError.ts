export class ApiHttpError extends Error {
  constructor(
    public readonly response: Response,
    public readonly data: unknown = null,
  ) {
    super(`API request failed with status ${response.status}`)
    this.name = 'ApiHttpError'
  }

  get status() {
    return this.response.status
  }
}
