using TeamsService.Models;

namespace TeamsService.Dtos.TeamMemberDto
{
    public class TeamMemberDto
    {
        public int UserId { get; set; }
        public int TeamId { get; set; }
        public bool IsModerator { get; set; }
        public string? About { get; set; }
        public Team? Team { get; set; }

        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
