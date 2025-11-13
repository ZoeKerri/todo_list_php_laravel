import 'package:intl/intl.dart';
import 'package:to_do_list_app/models/auth_response.dart';

enum Role { LEADER, MEMBER }

enum Priority { HIGH, MEDIUM, LOW }

class Team {
  int id;
  String name;
  String code;
  List<TeamMember> teamMembers;

  Team({
    required this.id,
    required this.name,
    required this.teamMembers,
    this.code='',
  });

  factory Team.fromJson(Map<String, dynamic> json) {
    var memberList = json['teamMembers'] as List?;
    List<TeamMember> members =
        memberList != null
            ? memberList
                .map((i) => TeamMember.fromJson(i as Map<String, dynamic>))
                .toList()
            : [];

    return Team(
      id: json['id'] as int,
      name: json['name'] as String? ?? 'Unknown',
      code: json['code'] as String? ?? '',
      teamMembers: members,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'teamMembers': teamMembers.map((m) => m.toJson()).toList(),
    };
  }
}

class TeamMember {
  int? id;
  Role role;
  int userId;
  int teamId;
  User? user;
  TeamMember({
    required this.id,
    required this.role,
    required this.userId,
    required this.teamId,
    this.user,
  });

  factory TeamMember.fromJson(Map<String, dynamic> json) {
    Role memberRole = Role.values.firstWhere(
      (e) => e.toString().split('.').last == json['role'],
      orElse: () => Role.MEMBER,
    );
    User? user;
    if (json['user'] != null && json['user'] is Map<String, dynamic>) {
      user = User.fromJson(json['user'] as Map<String, dynamic>);
    }
    return TeamMember(
      id: json['id'] as int,
      role: memberRole,
      userId: json['userId'] as int? ?? json['user']?['id'] as int? ?? 0,
      teamId: json['teamId'] as int? ?? json['team']?['id'] as int? ?? 0,
      user: user,
    );
  }

  Map<String, dynamic> toJson() {
    Map<String, dynamic> json = {
      'role': role.toString().split('.').last, // "LEADER" or "MEMBER"
      'user_id': userId, // Laravel expects snake_case: user_id
      'team_id': teamId, // Laravel expects snake_case: team_id
    };
    
    // Only include id if it's not null (for updates)
    if (id != null) {
      json['id'] = id;
    }
    
    // Include user data if available (optional, for responses)
    if (user != null) {
      json['user'] = user?.toJson();
    }
    
    return json;
  }
}

class TeamTask {
  int? id;
  String title;
  String? description;
  DateTime deadline;
  Priority priority;
  bool isCompleted;
  int teamMemberId;
  int? teamId; // Added teamId for API requests
  User? user;
  TeamTask({
    required this.id,
    required this.title,
    this.description,
    required this.deadline,
    required this.priority,
    required this.isCompleted,
    required this.teamMemberId,
    this.teamId,
    this.user,
  });
  factory TeamTask.fromJson(Map<String, dynamic> json) {
    Priority taskPriority = Priority.values.firstWhere(
      (e) => e.toString().split('.').last == json['priority'],
      orElse: () => Priority.LOW,
    );

    DateTime taskDeadline;
    try {
      taskDeadline = DateTime.parse(json['deadline'] as String);
    } catch (e) {
      try {
        taskDeadline = DateFormat(
          "dd/MM/yyyy HH:mm",
        ).parse(json['deadline'] as String);
      } catch (e2) {
        print("Error parsing date in both formats: ${json['deadline']} - $e2");
        taskDeadline = DateTime.now();
      }
    }

    return TeamTask(
      id: json['id'] as int,
      title: json['title'] as String? ?? 'Untitled Task',
      description: json['description'] as String?,
      deadline: taskDeadline,
      priority: taskPriority,
      // API returns isCompleted (camelCase) or is_completed (snake_case)
      isCompleted: json['isCompleted'] as bool? ?? 
                   json['is_completed'] as bool? ?? 
                   json['completed'] as bool? ?? 
                   false,
      teamMemberId:
          json['teamMemberId'] as int? ??
          json['memberId'] as int? ??
          json['member_id'] as int? ??
          json['teamMember']?['id'] as int? ??
          0,
    );
  }

  Map<String, dynamic> toJson() {
    // Laravel expects ISO format for deadline, not "dd/MM/yyyy HH:mm"
    String formattedDeadline = deadline.toIso8601String();

    Map<String, dynamic> json = {
      'id': id,
      'title': title,
      'description': description,
      'deadline': formattedDeadline,
      'priority': priority.toString().split('.').last, // Returns "LOW", "MEDIUM", "HIGH"
      'is_completed': isCompleted, // Laravel expects snake_case: is_completed
      'member_id': teamMemberId, // Laravel expects snake_case: member_id
    };
    
    // Include team_id if available (required for creating new tasks)
    if (teamId != null) {
      json['team_id'] = teamId;
    }
    
    return json;
  }
}